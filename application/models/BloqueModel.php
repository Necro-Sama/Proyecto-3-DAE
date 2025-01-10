<?php
class BloqueModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Obtener las fechas de inicio de las semanas
    function get_week_dates($start_date) {
        $weeks = [];
        $date = new DateTime($start_date);

        // Obtener las fechas de las 3 semanas
        for ($i = 0; $i < 3; $i++) {
            $start_of_week = clone $date;
            $start_of_week->modify('monday this week');  // Ajusta para el lunes de la semana
            $weeks[] = $start_of_week->format('Y-m-d');
            $date->modify('+1 week');
        }
        return $weeks;
    }

    // Función que carga los horarios
    public function horarios() {
        $current_date = date('Y-m-d'); // Fecha actual
        $weeks = $this->get_week_dates($current_date); // Obtener las fechas de las 3 semanas
        
        // Pasar las fechas al frontend
        $data['weeks'] = $weeks;

        // Cargar la vista
        $this->load->view('horarios_view', ['weeks' => $weeks]);  // Corregir la forma en que se pasa el array
    }

    // Obtener bloques de atención y bloqueados que se solapan con la fecha de agendamiento
    public function get_bloques_colisionando($carrera, $fecha_ini, $fecha_ter)
    {
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
                    FechaInicioSemana = TIMESTAMP(DATE(? - INTERVAL (DAYOFWEEK(?) - 2) DAY))
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
                    $fecha_ini,
                    $fecha_ini,
                    $carrera->RUNTS,
                    $carrera->ReemplazaRUNTS,
                    $fecha_ini,
                    $fecha_ter,
                ]
            )
            ->result();

        $bloques_bloqueados = $this->db
            ->query(
                "
                SELECT
                    *
                FROM
                    bloque bl
                JOIN
                    bloquebloqueado blb
                ON
                    blb.ID = bl.ID
                WHERE
                    FechaInicioSemana = TIMESTAMP(DATE(? - INTERVAL (DAYOFWEEK(?) - 2) DAY))
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
                    $fecha_ini,
                    $fecha_ini,
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

    // Agendar cita para el estudiante
    function agendar_estudiante($RUN_estudiante, $fecha_ini, $fecha_ter, $motivo)
    {
        $estudiante = $this->db
            ->query(
                "
                SELECT
                    *
                FROM
                    persona p
                JOIN
                    estudiante e
                ON
                    p.RUN = e.RUN
                WHERE
                    p.RUN = ?",
                [$RUN_estudiante]  // Corregir la consulta agregando el parámetro RUN
            )
            ->row(0);

        $carrera = $this->db
            ->query(
                "
                SELECT * FROM carrera
                WHERE COD_CARRERA = ?",
                [$estudiante->COD_CARRERA]  // Corregir la consulta para pasar el parámetro correctamente
            )
            ->row(0);

        $bloques_overlap = $this->get_bloques_colisionando($carrera, $fecha_ini, $fecha_ter);

        foreach ($bloques_overlap["atencion"] as $bloque) {
            if ($bloque->RUNCliente == $RUN_estudiante) {
                throw new Exception("No puedes agendar dos veces en la misma hora.");
            }
        }

        // Si existen bloqueos o colisiones con citas previas
        if (count($bloques_overlap["atencion"]) + count($bloques_overlap["bloqueado"]) >= 2) {
            throw new Exception("Horario no disponible.");
        }

        // Determinar con qué TS agendar la cita
        if (count($bloques_overlap["atencion"]) + count($bloques_overlap["bloqueado"]) == 0) {
            $run_ts = $carrera->RUNTS;  // Usar TS asignado si no hay conflictos
        } else {
            if (count($bloques_overlap["bloqueado"])) {
                $run_ts = $bloques_overlap["bloqueado"][0]->RUNTS == $carrera->ReemplazaRUNTS ? $carrera->RUNTS : $carrera->ReemplazaRUNTS;
            } elseif (count($bloques_overlap["atencion"])) {
                $run_ts = $bloques_overlap["atencion"][0]->RUNTS == $carrera->ReemplazaRUNTS ? $carrera->RUNTS : $carrera->ReemplazaRUNTS;
            }
        }

        // Verificar si el TS asignado tiene alguna licencia en ese rango de fechas
        $licencias = $this->db
            ->query(
                "
                SELECT
                    l.*
                FROM
                    Licencia l
                JOIN
                    persona ts
                ON
                    l.RUN = ts.RUN
                WHERE
                    l.RUN = ?
                AND
                    ? < TIMESTAMP(l.FECHA_TER)
                AND
                    ? > TIMESTAMP(l.FECHA_INI)",
                [$run_ts, $fecha_ini, $fecha_ter]
            )
            ->result();

        if (count($licencias)) {
            throw new Exception("Horario no disponible.");
        }

        // Verificar si la fecha es válida (mayor a la actual)
        if ($this->db->query("SELECT ? < NOW() AS xd", [$fecha_ter])->row(0)->xd) {
            throw new Exception("Fecha inválida.");
        }

        // Crear calendario semanal automáticamente si no existe
        if ($this->db->query(
            "SELECT * FROM calendariosemanal WHERE FechaInicioSemana = TIMESTAMP(DATE(? - INTERVAL (DAYOFWEEK(?) - 2) DAY)) AND RUNTS = ?",
            [$fecha_ini, $fecha_ini, $run_ts]
        )->num_rows() == 0) {
            $this->db->query(
                "INSERT INTO calendariosemanal VALUES (TIMESTAMP(DATE(? - INTERVAL (DAYOFWEEK(?) - 2) DAY)), ?)",
                [$fecha_ini, $fecha_ini, $run_ts]
            );
        }

        // Insertar el bloque de atención en la base de datos
        if (!$this->db->query(
            "INSERT INTO bloque VALUES (?, ?, NULL, TIMESTAMP(DATE(? - INTERVAL (DAYOFWEEK(?) - 2) DAY)), ?)",
            [$fecha_ini, $fecha_ter, $fecha_ini, $fecha_ini, $run_ts]
        )) {
            throw new Exception("ERROR " . $this->db->error()->code . ": " . $this->db->error()->message);
        }

        $insert_id = $this->db->insert_id();

        // Insertar el motivo de la cita
        if (!$this->db->query(
            "INSERT INTO bloqueatencion VALUES ('Reservado', ?, ?, ?)",
            [$motivo, $insert_id, $RUN_estudiante]
        )) {
            throw new Exception("DB ERROR " . $this->db->error()->code . ": " . $this->db->error()->message);
        }
    }

    // Función para obtener las fechas de las semanas
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

    // Obtener la fecha y hora actual de la base de datos
    function get_tiempo_bd()
    {
        return $this->db->query("SELECT NOW() AS t")->row(0)->t;
    }
    public function bloquearBloques($bloques) {
        foreach ($bloques as $bloque) {
            $data = [
                'dia' => $bloque['dia'],
                'bloque' => $bloque['bloque'],
                'inicio' => $bloque['inicio'],
                'fin' => $bloque['fin'],
                'asunto' => 'bloqueado',
                'rut' => $this->session->userdata('rut') // Usuario actual
            ];
            $this->db->insert('bloquebloqueado', $data);
        }
    }
    public function bloquear() {
        $bloques = $this->input->post('bloques');
        $this->BloqueModel->bloquearBloques($bloques);
        echo json_encode(['status' => 'success']);
    }
}

