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
    public function get_bloques_colisionando($carrera, $intervalo_colision)
    {
        return $this->db
            ->query(
                "
                SELECT
                    COUNT(*) cant
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
                AND
                    ? < FechaTermino
                AND
                    ? > FechaInicio
                ",
                [
                    $carrera->RUNTS,
                    $carrera->ReemplazaRUNTS,
                    $intervalo_colision[0],
                    $intervalo_colision[1],
                ]
            )
            ->row(0)->cant;
    }
    // Agenda solo para la semana actual
    function agendar_estudiante($RUN_estudiante, $dia, $bloque_horario, $motivo)
    {
        echo $RUN_estudiante, $dia, $bloque_horario, $motivo, "<br>";
        $estudiante = $this->db
            ->query(
                // "
                // SELECT e.COD_CARRERA, p.* FROM persona p, estudiante e
                // WHERE p.RUN = e.RUN
                // AND p.RUN = ?
                // ",
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
        // print_r($estudiante);
        $bloques = $this->get_bloques_carrera($estudiante->COD_CARRERA);
        // print_r($bloques);
        $bloques_atencion = $bloques["atencion"];
        $bloques_bloqueado = $bloques["bloqueados"];
        foreach ($bloques_atencion as $bloque) {
            print_r($bloque);
            echo "<br>";
        }
        $cant_overlap = $this->get_bloques_colisionando($carrera, [
            "2024-11-26 09:40:00",
            "2024-11-26 10:25:00",
        ]);
        // $res = $this->db
        // ->query(
        //     "
        //     INSERT INTO bloque
        //     "
        // );
        // print_r($res);
    }
}
