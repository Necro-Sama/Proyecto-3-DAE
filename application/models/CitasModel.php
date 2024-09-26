<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CitasModel extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function obtener_citas_estudiante($id_estudiante) {
        $this->db->select('citas.id_cita, citas.fecha, citas.hora, citas.id_trabajador_social');
        $this->db->from('citas');
        $this->db->where('citas.id_alumno', $id_estudiante);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function obtener_detalle_cita($id_cita) {
        $this->db->select('citas.id_cita, citas.fecha, citas.hora, citas.id_trabajador_social');
        $this->db->from('citas');
        $this->db->join('trabajadores_sociales', 'citas.id_trabajador_social = trabajadores_sociales.id');
        $this->db->where('citas.id', $id_cita);
        $query = $this->db->get();
        return $query->row_array();
    }
}
