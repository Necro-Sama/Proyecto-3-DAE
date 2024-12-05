<?php
class BloqueModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    public function get_bloques_carrera(
        $COD_CARRERA,
        $mostrar_semana_actual = true
    ) {
        // FechaInicioSemana = TIMESTAMP(DATE(NOW() - INTERVAL (DAYOFWEEK(NOW()) - 2) DAY))
        $carrera = $this->db
            ->query(
                "
                SELECT * FROM carrera
                WHERE COD_CARRERA = ?
                ",
                $COD_CARRERA
            )
            ->row(0);
        if ($mostrar_semana_actual) {
            $bloquesatencion = $this->db
                ->query(
                    // "
                    // SELECT bl.*, bla.*
                    // FROM calendariosemanal ca
                    // ,bloque bl, bloqueatencion bla
                    // WHERE ca.RUNTS = ? OR ca.RUNTS = ?
                    // AND ca.FechaInicioSemana = TIMESTAMP(DATE(NOW() - INTERVAL (DAYOFWEEK(NOW()) - 2) DAY))
                    // AND bl.FechaInicioSemana = ca.FechaInicioSemana
                    // AND bl.RUNTS = ca.RUNTS
                    // AND bl.ID = bla.ID
                    // ",
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
                        FechaInicioSemana = TIMESTAMP(DATE(NOW() - INTERVAL (DAYOFWEEK(NOW()) - 2) DAY))
                    AND (
                            RUNTS = ?
                        OR
                            RUNTS = ?
                    )
                    ",
                    [$carrera->RUNTS, $carrera->ReemplazaRUNTS]
                )
                ->result();
            $bloquesbloqueados = $this->db
                ->query(
                    // "
                    // SELECT bl.*, blb.* FROM calendariosemanal ca, bloque bl, bloquebloqueado blb
                    // WHERE ca.RUNTS = ? OR ca.RUNTS = ?
                    // AND ca.FechaInicioSemana = TIMESTAMP(DATE(NOW() - INTERVAL (DAYOFWEEK(NOW()) - 2) DAY))
                    // AND bl.FechaInicioSemana = ca.FechaInicioSemana
                    // AND bl.RUNTS = ca.RUNTS
                    // AND bl.ID = blb.ID
                    // ",
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
                        FechaInicioSemana = TIMESTAMP(DATE(NOW() - INTERVAL (DAYOFWEEK(NOW()) - 2) DAY))
                    AND (
                            RUNTS = ?
                        OR
                            RUNTS = ?
                    )
                    ",
                    [$carrera->RUNTS, $carrera->ReemplazaRUNTS]
                )
                ->result();
        } else {
        }
        return [
            "atencion" => $bloquesatencion,
            "bloqueados" => $bloquesbloqueados,
        ];
    }
        // Agregar en el controlador para calcular las fechas de las 3 semanas
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
    function agendar_estudiante(
        $RUN_estudiante,
        $fecha_ini,
        $fecha_ter,
        $motivo
    ) {
        // $horarios = [
        //     1 => [["08", "00"], ["08", "45"]],
        //     2 => [["08", "45"], ["09", "30"]],
        //     3 => [["09", "40"], ["10", "25"]],
        //     4 => [["10", "25"], ["11", "10"]],
        //     5 => [["11", "20"], ["12", "05"]],
        //     6 => [["12", "05"], ["12", "50"]],
        //     7 => [["14", "45"], ["15", "30"]],
        //     8 => [["15", "30"], ["16", "15"]],
        //     9 => [["16", "20"], ["17", "05"]],
        //     10 => [["17", "05"], ["17", "50"]],
        //     11 => [["17", "55"], ["18", "40"]],
        //     12 => [["18", "40"], ["19", "25"]],
        // ];
        // $dia_to_num = [
        //     "Lunes" => 0,
        //     "Martes" => 1,
        //     "Miércoles" => 2,
        //     "Jueves" => 3,
        //     "Viernes" => 4,
        // ];
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
                ",
                $RUN_estudiante
            )
            ->row(0);

        $carrera = $this->db
            ->query(
                "
                SELECT * FROM carrera
                WHERE COD_CARRERA = ?
                ",
                $estudiante->COD_CARRERA
            )
            ->row(0);

        $bloques_overlap = $this->get_bloques_colisionando(
            $carrera,
            $fecha_ini,
            $fecha_ter
        );
        print_r($bloques_overlap);
        foreach ($bloques_overlap["atencion"] as $bloque) {
            if ($bloque->RUNCliente == $RUN_estudiante) {
                throw new Exception(
                    "No puedes agendar dos veces en la misma hora."
                );
            }
        }
        // echo count($bloques_overlap["atencion"]);
        if (
            count($bloques_overlap["atencion"]) +
                count($bloques_overlap["bloqueado"]) >=
            2
        ) {
            throw new Exception("Horario no disponible.");
        }
        if (
            count($bloques_overlap["atencion"]) +
                count($bloques_overlap["bloqueado"]) ==
            0
        ) {
            // Agendar con TS asignada
            $run_ts = $carrera->RUNTS;
        } else {
            // Agendar con la TS reemplazante si el bloque NO asociado a este, sino, agendarlo a TS asignada
            if (count($bloques_overlap["bloqueado"])) {
                if (
                    $bloques_overlap["bloqueado"][0]->RUNTS ==
                    $carrera->ReemplazaRUNTS
                ) {
                    $run_ts = $carrera->RUNTS;
                } else {
                    $run_ts = $carrera->ReemplazaRUNTS;
                }
            } elseif (count($bloques_overlap["atencion"])) {
                if (
                    $bloques_overlap["atencion"][0]->RUNTS ==
                    $carrera->ReemplazaRUNTS
                ) {
                    $run_ts = $carrera->RUNTS;
                } else {
                    $run_ts = $carrera->ReemplazaRUNTS;
                }
            }
        }
        // $h = $horarios[$bloque_horario];
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
                    ? > TIMESTAMP(l.FECHA_INI)
                ",
                [$run_ts, $fecha_ini, $fecha_ter]
            )
            ->result();
        if (count($licencias)) {
            throw new Exception("Horario no disponible.");
        }
        if (
            $this->db->query("SELECT ? < NOW() AS xd", [$fecha_ter])->row(0)->xd
        ) {
            throw new Exception("Fecha inválida.");
        }
        // Si no existe calendario para esta semana, creelo automaticamente
        if (
            $this->db
                ->query(
                    "SELECT *
                 FROM calendariosemanal
                 WHERE FechaInicioSemana = TIMESTAMP(DATE(? - INTERVAL (DAYOFWEEK(?) - 2) DAY))
                 AND RUNTS = ?",
                    [$fecha_ini, $fecha_ini, $run_ts]
                )
                ->num_rows() == 0
        ) {
            echo "XD";
            $this->db->query(
                "INSERT INTO calendariosemanal VALUES
                 (TIMESTAMP(DATE(? - INTERVAL (DAYOFWEEK(?) - 2) DAY)), ?)",
                [$fecha_ini, $fecha_ini, $run_ts]
            );
        }
        if (
            !$this->db->query(
                "
                INSERT INTO bloque VALUES (
                    ?,
                    ?,
                    NULL,
                    TIMESTAMP(DATE(? - INTERVAL (DAYOFWEEK(?) - 2) DAY)),
                    ?
                )
                ",
                [$fecha_ini, $fecha_ter, $fecha_ini, $fecha_ini, $run_ts]
            )
        ) {
            throw new Exception(
                "ERROR " .
                    $this->db->error()->code .
                    ": " .
                    $this->db->error()->message
            );
        }
        $insert_id = $this->db->insert_id();
        if (
            !$this->db->query(
                "
                INSERT INTO bloqueatencion VALUES (
                    'Reservado',
                    ?,
                    ?,
                    ?
                )
                ",
                [$motivo, $insert_id, $RUN_estudiante]
            )
        ) {
            throw new Exception(
                "DB ERROR " .
                    $this->db->error()->code .
                    ": " .
                    $this->db->error()->message
            );
        }
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
}
