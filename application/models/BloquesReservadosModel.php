<?php
class BloquesReservadosModel extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    public function get_bloques_no_disponibles_carrera($carrera) {
        $este_lunes = date("Y-m-d", strtotime("last monday"));
        $en_dos_semanas = date("Y-m-d", strtotime("last monday +2 weeks"));

        // $this->db->select("fecha, num_bloque, carrera");
        // $this->db->from("bloques_reservados");
        // $this->db->where("carrera", $carrera);
        // $this->db->where("fecha >=", $este_lunes);
        // $this->db->where("fecha <", $en_dos_semanas);
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
        // return $this->db->get();
    }
}
