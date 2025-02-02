<?php
class BloqueModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    function get_week_dates($start_date) {
        $weeks = [];
        $date = new DateTime($start_date);

        // Obtenemos las fechas de las 3 semanas
        for ($i = 0; $i < 3; $i++) {
            $start_of_week = clone $date;
            $start_of_week->modify('monday this week');  // Ajusta para el lunes de la semana
            $weeks[] = $start_of_week->format('Y-m-d');
            $date->modify('+1 week');
        }
        return $weeks;
    }

    public function horarios() {
        $current_date = date('Y-m-d'); // Fecha actual
        $weeks = $this->get_week_dates($current_date); // Obtener las fechas de las 3 semanas
        
        // Pasar las fechas al frontend
        $data['weeks'] = $weeks;

        // Cargar la vista
        $this->load->view('horarios_view', $data['weeks']);
    }

    public function get_bloques_colisionando($carrera, $fecha_ini, $fecha_ter)
    {
        // Calcular FechaInicioSemana en PHP
        $fecha_inicio_semana = date(
            'Y-m-d H:i:s',
            strtotime($fecha_ini . ' -' . (date('N', strtotime($fecha_ini)) - 1) . ' days')
        );

        // Obtener los bloques de atención
        $bloques_atencion = $this->db
            ->query(
                "
                SELECT
                    *
                FROM
                    bloque bl
                JOIN
                    bloqueatencion bla
                ON
                    bla.ID = bl.ID
                WHERE
                    FechaInicioSemana = TIMESTAMP(DATE(?))
                AND (
                        RUNTS = ?
                    OR
                        RUNTS = ?
                )
                AND
                    ? < FechaTermino
                AND
                    ? > FechaInicio
                ",
                [
                    $fecha_inicio_semana,
                    $carrera->RUNTS,
                    $carrera->ReemplazaRUNTS,
                    $fecha_ini,
                    $fecha_ter,
                ]
            )
            ->result();

        // Calcular FechaInicioSemana en PHP
        $fecha_inicio_semana = date('Y-m-d', strtotime($fecha_ini . ' -' . (date('N', strtotime($fecha_ini)) - 1) . ' days'));

        // Ejecutar la consulta con la fecha ya procesada
        $bloques_bloqueados = $this->db
        ->query(
            "
            SELECT
                *
            FROM
                bloque bl
            LEFT JOIN
                bloquebloqueado blb
            ON
                blb.ID = bl.ID
            WHERE
                FechaInicioSemana = ?
            AND (
                RUNTS = ? OR RUNTS = ?
            )
            AND ? < bl.FechaTermino
            AND ? > bl.FechaInicio
            ",
            [
                $fecha_inicio_semana, // Fecha calculada de inicio de semana
                $carrera->RUNTS,
                $carrera->ReemplazaRUNTS,
                $fecha_ini,
                $fecha_ter,
            ]
        )
        ->result();

        return [
            "atencion" => $bloques_atencion,
            "bloqueado" => $bloques_bloqueados,
        ];
    }

    function agendar_estudiante($RUN_estudiante, $fecha_ini, $fecha_ter, $motivo) {
        $this->db->trans_start();

        try {
            // Verificar si el bloque ya está reservado
            $bloque_reservado = $this->db->query("
                SELECT ba.* 
                FROM bloque b
                JOIN bloqueatencion ba ON b.ID = ba.ID
                WHERE b.FechaInicio = ? 
                AND b.FechaTermino = ?
                AND ba.Estado = 'Reservado'
            ", array($fecha_ini, $fecha_ter))->num_rows() > 0;

            if ($bloque_reservado) {
                throw new Exception("Este horario ya ha sido reservado. Por favor, seleccione otro horario.");
            }

            // Obtener datos de persona
            $persona = $this->obtener_datos_persona($RUN_estudiante);
            if (!$persona) {
                throw new Exception("El RUN no está registrado en el sistema.");
            }

            // Verificar si es estudiante
            $estudiante = $this->db->query("
                SELECT * FROM estudiante WHERE RUN = ?
            ", array($RUN_estudiante))->row();

            // Determinar el trabajador social
            if (!$estudiante) {
                // Verificar/crear cliente
                $cliente_existe = $this->db->query("
                    SELECT * FROM cliente WHERE RUN = ?
                ", array($RUN_estudiante))->num_rows() > 0;

                if (!$cliente_existe) {
                    $this->db->query("
                        INSERT INTO cliente (RUN) VALUES (?)
                    ", array($RUN_estudiante));
                }
                
                $run_ts = $this->obtenerTSDisponible();
            } else {
                $carrera = $this->db->query("
                    SELECT * FROM carrera WHERE COD_CARRERA = ?
                ", array($estudiante->COD_CARRERA))->row();

                if (!$carrera) {
                    throw new Exception("No se encontró la carrera del estudiante.");
                }
                $run_ts = $carrera->RUNTS;
            }

            // Verificar que tengamos un TS asignado
            if (!$run_ts) {
                throw new Exception("No se pudo asignar un trabajador social.");
            }

            // Obtener fecha inicio de semana
            $fecha_inicio_semana = date('Y-m-d', strtotime('monday this week', strtotime($fecha_ini)));

            // Verificar/crear calendario semanal
            $calendario_existe = $this->db->query("
                SELECT * FROM calendariosemanal 
                WHERE FechaInicioSemana = ? AND RUNTS = ?
            ", array($fecha_inicio_semana, $run_ts))->num_rows() > 0;

            if (!$calendario_existe) {
                $this->db->query("
                    INSERT INTO calendariosemanal (FechaInicioSemana, RUNTS) 
                    VALUES (?, ?)
                ", array($fecha_inicio_semana, $run_ts));
            }

            // Insertar bloque
            $result = $this->db->query("
                INSERT INTO bloque (FechaInicio, FechaTermino, FechaInicioSemana, RUNTS) 
                VALUES (?, ?, ?, ?)
            ", array($fecha_ini, $fecha_ter, $fecha_inicio_semana, $run_ts));

            if (!$result) {
                throw new Exception("Error al crear el bloque de atención.");
            }

            $bloque_id = $this->db->insert_id();

            // Insertar bloque atención
            $result = $this->db->query("
                INSERT INTO bloqueatencion (Estado, Motivo, ID, RUNCliente) 
                VALUES ('Reservado', ?, ?, ?)
            ", array($motivo, $bloque_id, $RUN_estudiante));

            if (!$result) {
                throw new Exception("Error al registrar la atención.");
            }

            // Intentar enviar el correo sin afectar la transacción
            $correo_enviado = false;
            try {
                $ts_datos = $this->obtener_datos_persona($run_ts);
                $this->enviar_correo_confirmacion($persona, $ts_datos, $fecha_ini, $fecha_ter, $motivo);
                $correo_enviado = true;
            } catch (Exception $e) {
                // Continuar con la transacción aunque falle el correo
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                throw new Exception("Error en la transacción al agendar la cita.");
            }

            if (!$correo_enviado) {
                $this->session->set_flashdata('warning', 
                    'La cita se ha agendado correctamente, pero hubo un problema al enviar el correo de confirmación.');
            }

            return true;

        } catch (Exception $e) {
            $this->db->trans_rollback();
            throw $e;
        }
    }

    private function obtener_datos_persona($run) {
        return $this->db->query("
            SELECT Nombre, Apellido, Correo, RUN 
            FROM persona 
            WHERE RUN = ?
        ", array($run))->row();
    }

    private function obtenerTSDisponible() {
        $query = $this->db->query("
            SELECT 
                ts.RUN
            FROM 
                trabajadorsocial ts
                JOIN persona p ON ts.RUN = p.RUN
            WHERE 
                p.Activo = 1
                AND NOT EXISTS (
                    SELECT 1 
                    FROM licencia l 
                    WHERE l.RUN = ts.RUN 
                    AND CURRENT_DATE BETWEEN l.FECHA_INI AND l.FECHA_TER
                )
            ORDER BY (
                SELECT COUNT(*) 
                FROM bloque b 
                JOIN bloqueatencion ba ON b.ID = ba.ID 
                WHERE b.RUNTS = ts.RUN 
                AND b.FechaInicio > CURRENT_TIMESTAMP
            ) ASC
            LIMIT 1
        ");

        $ts = $query->row();
        
        if (!$ts) {
            throw new Exception("No hay trabajadores sociales disponibles en este momento.");
        }

        return $ts->RUN;
    }

    private function enviar_correo_confirmacion($persona, $ts_datos, $fecha_ini, $fecha_ter, $motivo) {
        $this->load->library('email');
        
        $this->email->from($this->config->item('smtp_user'), 'Sistema de Citas UTA');
        $this->email->to($persona->Correo);
        $this->email->subject('Confirmación de Cita - Trabajador Social UTA');

        $fecha_formateada = date('d/m/Y', strtotime($fecha_ini));
        $hora_inicio = date('H:i', strtotime($fecha_ini));
        $hora_fin = date('H:i', strtotime($fecha_ter));

        $mensaje = "
            <html>
            <head>
                <title>Confirmación de Cita</title>
            </head>
            <body>
                <h2>Confirmación de Cita - Trabajador Social UTA</h2>
                <p>Estimado/a {$persona->Nombre} {$persona->Apellido},</p>
                <p>Su cita ha sido agendada exitosamente con los siguientes detalles:</p>
                <ul>
                    <li><strong>Fecha:</strong> {$fecha_formateada}</li>
                    <li><strong>Horario:</strong> {$hora_inicio} - {$hora_fin}</li>
                    <li><strong>Trabajador Social:</strong> {$ts_datos->Nombre} {$ts_datos->Apellido}</li>
                    <li><strong>Motivo:</strong> {$motivo}</li>
                </ul>
                <p>Por favor, asegúrese de llegar a tiempo a su cita.</p>
                <p>Si necesita reagendar o cancelar su cita, por favor hágalo con anticipación a través del sistema.</p>
                <br>
                <p>Saludos cordiales,</p>
                <p>Equipo de Trabajo Social UTA</p>
            </body>
            </html>
        ";

        $this->email->message($mensaje);
        return $this->email->send();
    }

    function get_semanas($num_semanas)
    {
        $query = "SELECT ";
        for ($i = 0; $i < $num_semanas; $i++) {
            $query .= "TIMESTAMP(DATE(NOW() - INTERVAL (DAYOFWEEK(NOW()) - 2) DAY)) + INTERVAL $i WEEK AS s$i";
            if ($i < $num_semanas - 1) {
                $query .= ", ";
            }
        }
        return array_values($this->db->query($query)->result_array()[0]);
    }
    function get_tiempo_bd()
    {
        return $this->db->query("SELECT NOW() AS t")->row(0)->t;
    }
    function bloquear($fecha_inicio, $fecha_termino) {
        $this->db->insert('bloquebloqueado', $fecha_inicio, $fecha_termino);
    }
    public function verificar_bloque($id)
    {
        $this->db->where('ID', $id);
        $query = $this->db->get('bloque');
        return $query->num_rows() > 0;
    }

    public function obtener_bloques_por_dia_ts($run_trabajador, $fecha) {
        $fecha_inicio = date('Y-m-d 00:00:00', strtotime($fecha));
        $fecha_fin = date('Y-m-d 23:59:59', strtotime($fecha));

        $this->db->where('run_trabajador', $run_trabajador);
        $this->db->where('fechainicio >=', $fecha_inicio);
        $this->db->where('fechainicio <=', $fecha_fin);
        
        return $this->db->get('bloque')->result();
    }

    public function verificar_bloque_disponible($fecha_inicio, $fecha_fin, $run_ts) {
        // Primero, asegurarnos de que las fechas estén en el formato correcto
        $fecha_inicio = date('Y-m-d H:i:s', strtotime($fecha_inicio));
        $fecha_fin = date('Y-m-d H:i:s', strtotime($fecha_fin));

        $this->db->select('COUNT(*) as total');
        $this->db->from('bloque');
        $this->db->where('RUNTS', $run_ts);
        $this->db->where("(
            (FechaInicio <= '$fecha_inicio' AND FechaTermino >= '$fecha_inicio')
            OR (FechaInicio <= '$fecha_fin' AND FechaTermino >= '$fecha_fin')
            OR (FechaInicio >= '$fecha_inicio' AND FechaTermino <= '$fecha_fin')
        )");

        $query = $this->db->get();
        $result = $query->row();
        
        // Para debug
        log_message('debug', 'SQL Query: ' . $this->db->last_query());
        log_message('debug', 'Resultado: ' . print_r($result, true));

        return ($result->total > 0);
    }

    public function bloquear_horario($datos) {
        try {
            log_message('debug', '[INICIO] bloquear_horario - Datos recibidos: ' . json_encode($datos));

            // Validar datos de entrada
            if (empty($datos['ID']) || empty($datos['RUNTS']) || 
                empty($datos['FechaInicio']) || empty($datos['FechaTermino'])) {
                log_message('error', 'Datos incompletos: ' . json_encode($datos));
                return false;
            }

            // Formatear fechas
            $fecha_inicio = date('Y-m-d H:i:s', strtotime($datos['FechaInicio']));
            $fecha_termino = date('Y-m-d H:i:s', strtotime($datos['FechaTermino']));
            
            log_message('debug', 'Fechas formateadas: Inicio=' . $fecha_inicio . ', Fin=' . $fecha_termino);

            // Iniciar transacción
            $this->db->trans_start();

            try {
                // 1. Insertar en bloque
                $bloque_data = [
                    'ID' => $datos['ID'],
                    'RUNTS' => $datos['RUNTS'],
                    'FechaInicio' => $fecha_inicio,
                    'FechaTermino' => $fecha_termino
                ];

                log_message('debug', 'Intentando insertar en bloque: ' . json_encode($bloque_data));
                
                // Verificar si el bloque ya existe
                $bloque_existe = $this->db->where('ID', $datos['ID'])
                                        ->get('bloque')
                                        ->num_rows() > 0;

                if (!$bloque_existe) {
                    if (!$this->db->insert('bloque', $bloque_data)) {
                        $error = $this->db->error();
                        log_message('error', 'Error al insertar en bloque: ' . json_encode($error));
                        throw new Exception('Error al insertar en la tabla bloque');
                    }
                    log_message('debug', 'Bloque insertado correctamente');
                } else {
                    log_message('debug', 'Bloque ya existe, continuando...');
                }

                // 2. Insertar en bloquebloqueado
                $bloqueo_data = [
                    'ID' => $datos['ID'],
                    'fechainicio' => $fecha_inicio,
                    'fechafinal' => $fecha_termino,
                    'RUN' => $datos['RUNTS']
                ];

                log_message('debug', 'Intentando insertar en bloquebloqueado: ' . json_encode($bloqueo_data));

                // Primero eliminar si existe
                $this->db->where('ID', $datos['ID'])->delete('bloquebloqueado');
                
                if (!$this->db->insert('bloquebloqueado', $bloqueo_data)) {
                    $error = $this->db->error();
                    log_message('error', 'Error al insertar en bloquebloqueado: ' . json_encode($error));
                    throw new Exception('Error al insertar en la tabla bloquebloqueado');
                }
                
                log_message('debug', 'Bloqueo insertado correctamente');

                // Completar transacción
                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    $error = $this->db->error();
                    log_message('error', 'Error en transacción final: ' . json_encode($error));
                    throw new Exception('Error en la transacción de base de datos');
                }

                log_message('debug', '[FIN] bloquear_horario - Proceso completado exitosamente');
                return true;

            } catch (Exception $e) {
                log_message('error', 'Error específico en bloqueo: ' . $e->getMessage());
                $this->db->trans_rollback();
                throw $e;
            }

        } catch (Exception $e) {
            log_message('error', 'Error general en bloqueo_horario: ' . $e->getMessage());
            return false;
        }
    }

    public function agendar_cita($usuario, $fecha_ini, $fecha_ter, $motivo, $runTS = null) {
        try {
            // Desactivar autocommit temporalmente
            $this->db->query('SET autocommit=0');
            
            // Generar ID único
            $bloque_id = 'AGD' . date('YmdHis') . sprintf('%04d', mt_rand(0, 9999));
            
            // Obtener fecha inicio de semana
            $fecha_inicio_semana = date('Y-m-d', strtotime('monday this week', strtotime($fecha_ini)));

            // Log de datos iniciales
            log_message('debug', 'Iniciando agendamiento con datos: ' . json_encode([
                'bloque_id' => $bloque_id,
                'fecha_inicio_semana' => $fecha_inicio_semana,
                'runTS' => $runTS,
                'fecha_ini' => $fecha_ini,
                'fecha_ter' => $fecha_ter
            ]));

            // Verificar y crear calendario semanal
            $sql_check_calendar = "SELECT * FROM calendariosemanal WHERE FechaInicioSemana = ? AND RUNTS = ?";
            $calendario_existe = $this->db->query($sql_check_calendar, array($fecha_inicio_semana, $runTS))->num_rows() > 0;

            if (!$calendario_existe) {
                $sql_insert_calendar = "INSERT INTO calendariosemanal (FechaInicioSemana, RUNTS) VALUES (?, ?)";
                if (!$this->db->query($sql_insert_calendar, array($fecha_inicio_semana, $runTS))) {
                    throw new Exception('Error al crear el calendario semanal');
                }
                log_message('debug', 'Calendario semanal creado');
            } else {
                log_message('debug', 'Calendario semanal ya existe');
            }

            // Insertar bloque
            $sql_insert_bloque = "INSERT INTO bloque (ID, FechaInicio, FechaTermino, FechaInicioSemana, RUNTS) 
                                 VALUES (?, ?, ?, ?, ?)";
            
            $result_bloque = $this->db->query($sql_insert_bloque, array(
                $bloque_id,
                $fecha_ini,
                $fecha_ter,
                $fecha_inicio_semana,
                $runTS
            ));

            if (!$result_bloque) {
                $error = $this->db->error();
                log_message('error', 'Error al insertar bloque: ' . json_encode($error));
                throw new Exception('Error al crear el bloque: ' . $error['message']);
            }

            // Verificar la inserción del bloque inmediatamente
            $sql_verify_bloque = "SELECT * FROM bloque WHERE ID = ?";
            $bloque_result = $this->db->query($sql_verify_bloque, array($bloque_id));
            
            if ($bloque_result->num_rows() == 0) {
                log_message('error', 'Bloque no encontrado después de inserción');
                throw new Exception('El bloque no se creó correctamente');
            }

            log_message('debug', 'Bloque creado exitosamente');

            // Commit después de crear el bloque
            $this->db->query('COMMIT');
            log_message('debug', 'Commit realizado después de crear bloque');

            // Insertar en bloqueatencion
            $sql_insert_atencion = "INSERT INTO bloqueatencion (ID, RUNCliente, Motivo, Estado) 
                                   VALUES (?, ?, ?, ?)";
            
            $result_atencion = $this->db->query($sql_insert_atencion, array(
                $bloque_id,
                $usuario['RUN'],
                $motivo,
                'Agendado'
            ));

            if (!$result_atencion) {
                $error = $this->db->error();
                log_message('error', 'Error al insertar atención: ' . json_encode($error));
                // Eliminar el bloque si falla la atención
                $this->db->query("DELETE FROM bloque WHERE ID = ?", array($bloque_id));
                throw new Exception('Error al crear la atención: ' . $error['message']);
            }

            log_message('debug', 'Atención creada exitosamente');

            // Commit final
            $this->db->query('COMMIT');
            
            // Restaurar autocommit
            $this->db->query('SET autocommit=1');

            log_message('debug', 'Proceso de agendamiento completado exitosamente');
            return $bloque_id;

        } catch (Exception $e) {
            // Rollback en caso de error
            $this->db->query('ROLLBACK');
            $this->db->query('SET autocommit=1');
            log_message('error', 'Error en agendar_cita: ' . $e->getMessage());
            throw new Exception('Error al agendar la cita: ' . $e->getMessage());
        }
    }

    public function obtener_ts_por_carrera($run_estudiante) {
        try {
            // Primero obtenemos la carrera del estudiante
            $this->db->select('c.ID as CarreraID')
                ->from('estudiante e')
                ->join('carrera c', 'e.CarreraID = c.ID')
                ->where('e.RUN', $run_estudiante);
            
            $query = $this->db->get();
            if ($query->num_rows() == 0) {
                return null;
            }
            
            $carrera = $query->row_array();
            
            // Ahora buscamos el TS principal activo para esta carrera
            $this->db->select('ts.RUN')
                ->from('trabajadorsocial ts')
                ->join('persona p', 'ts.RUN = p.RUN')
                ->join('carrera_ts cts', 'ts.RUN = cts.RUNTS')
                ->where('cts.CarreraID', $carrera['CarreraID'])
                ->where('p.Activo', 1)
                ->where('cts.EsPrincipal', 1);
            
            $query = $this->db->get();
            
            // Si encontramos un TS principal activo, lo retornamos
            if ($query->num_rows() > 0) {
                return $query->row()->RUN;
            }
            
            // Si no hay TS principal activo, buscamos el TS de reemplazo
            $this->db->select('ts.RUN')
                ->from('trabajadorsocial ts')
                ->join('persona p', 'ts.RUN = p.RUN')
                ->join('carrera_ts cts', 'ts.RUN = cts.RUNTS')
                ->where('cts.CarreraID', $carrera['CarreraID'])
                ->where('p.Activo', 1)
                ->where('cts.EsPrincipal', 0);
            
            $query = $this->db->get();
            
            if ($query->num_rows() > 0) {
                return $query->row()->RUN;
            }
            
            return null;
        } catch (Exception $e) {
            log_message('error', 'Error en obtener_ts_por_carrera: ' . $e->getMessage());
            return null;
        }
    }

    public function obtener_primer_ts_disponible() {
        try {
            $this->db->select('ts.RUN')
                ->from('trabajadorsocial ts')
                ->join('persona p', 'ts.RUN = p.RUN')
                ->where('p.Activo', 1)
                ->order_by('RAND()')
                ->limit(1);
            
            $query = $this->db->get();
            
            if ($query->num_rows() > 0) {
                return $query->row()->RUN;
            }
            
            return null;
        } catch (Exception $e) {
            log_message('error', 'Error en obtener_primer_ts_disponible: ' . $e->getMessage());
            return null;
        }
    }

    public function obtener_runts($id_cita) {
        $this->db->select('RUNTS')
                 ->from('bloque')
                 ->where('ID', $id_cita);
         
        $query = $this->db->get();
        $result = $query->row();
         
        return $result ? $result->RUNTS : null;
    }
}
