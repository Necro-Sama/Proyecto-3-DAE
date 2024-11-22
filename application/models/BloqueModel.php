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
        // $result_bloqueatencion = $this->db->query("
        // SELECT * FROM bloqueatencion
        // ")->result_array();
        // print_r($result_bloqueatencion);

        // $result_bloque = $this->db->query("
        // SELECT * FROM bloque
        // ")->result_array();
        // print_r($result_bloque);

        // $result_ts = $this->db->query("
        // SELECT * FROM trabajadorsocial
        // ")->result_array();
        // print_r($result_ts);

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
        // print_r($carrera);
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
                ->result_array();
            
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
                ->result_array();
        } else {
            $calendarios = $this->db
                ->query(
                    "
                    SELECT * FROM calendariosemanal
                    WHERE RUNTS = ?
                    OR RUNTS = ?
                    ",
                    [$carrera->RUNTS, $carrera->ReemplazaRUNTS]
                )
                ->result_array();
        }
        echo "BLOQUES ATENCION:";
        print_r($bloquesatencion);
        echo "<br>";
        echo "BLOQUES BLOQUEADOS: ";
        print_r($bloquesbloqueados);
        echo "<br>";


        // $bloques = $this->db
        //     ->query(
        //         "
        //         SELECT * FROM bloque b, bloqueatencion ba
        //         WHERE b.ID = ba.ID
        //         "
        //     )
        //     ->result_array();
        // print_r($bloques);
        // $RUN_trabajadorsocial= $this->db->query("
        //     SELECT * FROM trabajadorsocial ts, trabajadorsocial tsreem
        //     WHERE ts
        // ", $COD_CARRERA)->result_array();
        // print_r($RUN_trabajadorsocial);

        // return $result_bloqueatencion;
    }
    // public function get_bloques_no_disponibles_carrera($cod_carrera) {
    //     return $this->db->query("
    //     SELECT * FROM (
    //         SELECT fecha, num_bloque, COUNT(*) cant FROM bloques_reservados
    //         WHERE carrera=?
    //         AND fecha >= DATE(DATE_ADD(NOW(), INTERVAL(-WEEKDAY(NOW())) DAY))
    //         AND fecha < DATE_ADD(DATE(DATE_ADD(NOW(), INTERVAL(-WEEKDAY(NOW())) DAY)), INTERVAL 2 WEEK)
    //         GROUP BY fecha, num_bloque) b
    //     WHERE cant >= (
    //         SELECT COUNT(*)
    //         FROM usuarios
    //         WHERE user_type='ts'
    //         AND carrera=?)
    //     ", array($cod_carrera, $cod_carrera));
    // }
    // public function get_bloques_no_disponibles_ts($cod_carrera) {
    //     return $this->db->query("
    //     SELECT * FROM (
    //         SELECT fecha, num_bloque, COUNT(*) cant FROM bloques_reservados
    //         WHERE carrera=?
    //         AND fecha >= DATE(DATE_ADD(NOW(), INTERVAL(-WEEKDAY(NOW())) DAY))
    //         AND fecha < DATE_ADD(DATE(DATE_ADD(NOW(), INTERVAL(-WEEKDAY(NOW())) DAY)), INTERVAL 2 WEEK)
    //         GROUP BY fecha, num_bloque) b
    //     WHERE cant >= (
    //         SELECT COUNT(*)
    //         FROM usuarios
    //         WHERE user_type='ts'
    //         AND carrera=?)
    //     ", array($cod_carrera, $cod_carrera));
    // }
    // public function agendar($usuario, $fecha, $num_bloque, $motivo) {
    //     $minutos_por_bloque = 30;
    //     $ts_disponible = $this->db->query("
    //         SELECT * FROM usuarios u
    //         WHERE user_type = 'ts'
    //         AND ? > NOW()
    //         AND carrera = ?
    //         AND NOT EXISTS (SELECT * FROM bloques_reservados br
    //                         WHERE br.ta_id = u.id
    //                         AND br.fecha = ?
    //                         AND br.num_bloque = ?)
    //     ", array(date(" Y-m-d H:i:s", $fecha + strtotime($minutos_por_bloque*$num_bloque." minutes", 0)), $usuario->carrera, date("Y-m-d", $fecha), $num_bloque));
    //     if ($ts_disponible->num_rows()) {
    //         $ts_disponible = $ts_disponible->row(0);
    //         return $this->db->query("
    //             INSERT INTO bloques_reservados VALUES
    //             (NULL, ?, ?, ?, ?, ?, ?)
    //         ", array(date("Y-m-d", $fecha), $num_bloque, $ts_disponible->id, $usuario->id, $usuario->carrera, $motivo));
    //     }

    // }
}
