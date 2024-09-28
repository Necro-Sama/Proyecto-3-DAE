<?php
class BloquesReservadosModel extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    public function get_bloques_no_disponibles_carrera($carrera) {
        $este_lunes = date("Y-m-d", strtotime("last monday"));
        $en_dos_semanas = date("Y-m-d", strtotime("last monday +2 weeks"));
        return $this->db->query("
        SELECT * FROM (
            SELECT fecha, num_bloque, COUNT(*) cant FROM bloques_reservados
            WHERE carrera=?
            AND fecha >= ?
            AND fecha < ?
            GROUP BY fecha, num_bloque) b
        WHERE cant >= (
            SELECT COUNT(*)
            FROM usuarios
            WHERE user_type='ts'
            AND carrera=?)
        ", array($carrera, $este_lunes, $en_dos_semanas, $carrera));
    }
}
