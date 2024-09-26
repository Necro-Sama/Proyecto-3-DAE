<?php
class BloquesReservadosModel extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    public function get_bloques_reservados_carrera($carrera) {
        $this->db->select("*");
        $this->db->from("bloques_reservados");
        $this->db->where("carrera", $carrera);
        return $this->db->get();
    }
}
