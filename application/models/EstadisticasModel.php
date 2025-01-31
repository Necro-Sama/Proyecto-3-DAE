<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EstadisticasModel extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function obtenerEstadisticas($fecha_inicio = null, $fecha_fin = null) {
        // Si no se proporcionan fechas, usar el año actual
        if (!$fecha_inicio || !$fecha_fin) {
            $fecha_inicio = date('Y-01-01');
            $fecha_fin = date('Y-12-31');
        }

        return [
            'por_carrera' => $this->obtenerEstadisticasPorCarrera($fecha_inicio, $fecha_fin),
            'por_motivo' => $this->obtenerEstadisticasPorMotivo($fecha_inicio, $fecha_fin),
            'estados' => $this->obtenerEstadisticasPorEstado($fecha_inicio, $fecha_fin),
            'total_citas' => $this->obtenerTotalCitas($fecha_inicio, $fecha_fin)
        ];
    }

    private function obtenerEstadisticasPorCarrera($fecha_inicio, $fecha_fin) {
        $this->db->select('c.Nombre as nombre_carrera, COUNT(*) as total');
        $this->db->from('bloqueatencion ba');
        $this->db->join('estudiante e', 'ba.RUNCliente = e.RUN', 'left');
        $this->db->join('carrera c', 'e.COD_CARRERA = c.COD_CARRERA', 'left');
        $this->db->join('bloque b', 'ba.ID = b.ID');
        $this->db->where('b.FechaInicio >=', $fecha_inicio);
        $this->db->where('b.FechaInicio <=', $fecha_fin);
        $this->db->group_by('c.COD_CARRERA');
        return $this->db->get()->result_array();
    }

    private function obtenerEstadisticasPorMotivo($fecha_inicio, $fecha_fin) {
        $this->db->select('Motivo, COUNT(*) as total');
        $this->db->from('bloqueatencion ba');
        $this->db->join('bloque b', 'ba.ID = b.ID');
        $this->db->where('b.FechaInicio >=', $fecha_inicio);
        $this->db->where('b.FechaInicio <=', $fecha_fin);
        $this->db->group_by('Motivo');
        return $this->db->get()->result_array();
    }

    private function obtenerEstadisticasPorEstado($fecha_inicio, $fecha_fin) {
        $this->db->select('Estado, COUNT(*) as total');
        $this->db->from('bloqueatencion ba');
        $this->db->join('bloque b', 'ba.ID = b.ID');
        $this->db->where('b.FechaInicio >=', $fecha_inicio);
        $this->db->where('b.FechaInicio <=', $fecha_fin);
        $this->db->group_by('Estado');
        return $this->db->get()->result_array();
    }

    private function obtenerTotalCitas($fecha_inicio, $fecha_fin) {
        $this->db->from('bloqueatencion ba');
        $this->db->join('bloque b', 'ba.ID = b.ID');
        $this->db->where('b.FechaInicio >=', $fecha_inicio);
        $this->db->where('b.FechaInicio <=', $fecha_fin);
        return $this->db->count_all_results();
    }
}
