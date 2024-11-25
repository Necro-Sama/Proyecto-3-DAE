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
                    "
                    SELECT bl.*, bla.* FROM calendariosemanal ca, bloque bl, bloqueatencion bla
                    WHERE ca.RUNTS = ? OR ca.RUNTS = ?
                    AND ca.FechaInicioSemana = TIMESTAMP(DATE(NOW() - INTERVAL (DAYOFWEEK(NOW()) - 2) DAY))
                    AND bl.FechaInicioSemana = ca.FechaInicioSemana
                    AND bl.RUNTS = ca.RUNTS
                    AND bl.ID = bla.ID
                    ",
                    [$carrera->RUNTS, $carrera->ReemplazaRUNTS]
                )
                ->result();
            $bloquesbloqueados = $this->db
                ->query(
                    "
                    SELECT bl.*, blb.* FROM calendariosemanal ca, bloque bl, bloquebloqueado blb
                    WHERE ca.RUNTS = ? OR ca.RUNTS = ?
                    AND ca.FechaInicioSemana = TIMESTAMP(DATE(NOW() - INTERVAL (DAYOFWEEK(NOW()) - 2) DAY))
                    AND bl.FechaInicioSemana = ca.FechaInicioSemana
                    AND bl.RUNTS = ca.RUNTS
                    AND bl.ID = blb.ID
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
    // Agenda solo para la semana actual
    function agendar_estudiante($RUN_estudiante, $dia, $bloque_horario, $motivo)
    {
        echo $RUN_estudiante .
            "," .
            $dia .
            "," .
            $bloque_horario .
            "," .
            $motivo .
            "         ";
        $estudiante = $this->db
            ->query(
                "
            SELECT e.COD_CARRERA, p.* FROM persona p, estudiante e
            WHERE p.RUN = e.RUN
            AND p.RUN = ?
            ",
                [$RUN_estudiante]
            )
            ->row(0);
        // print_r($estudiante);
        $bloques = $this->get_bloques_carrera($estudiante->COD_CARRERA);
        // print_r($bloques);
        $bloques_atencion = $bloques["atencion"];
        foreach ($bloques_atencion as $value) {
            print_r($value->FechaInicio);
        }
        // $res = $this->db
        // ->query(
        //     "
        //     INSERT INTO bloque
        //     "
        // );
        // print_r($res);
    }
}
