<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EstadisticasModel extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function obtenerEstadisticas() {
        return [
            'por_carrera' => $this->obtenerEstadisticasPorCarrera(),
            'por_motivo' => $this->obtenerEstadisticasPorMotivo(),
            'por_estado' => $this->obtenerEstadisticasPorEstado()
        ];
    }

    private function obtenerEstadisticasPorCarrera() {
        $sql = "
            SELECT 
                COALESCE(c.Nombre, 'Sin Carrera') as nombre,
                COUNT(ba.ID) as total
            FROM bloque b
            INNER JOIN bloqueatencion ba ON b.ID = ba.ID
            LEFT JOIN cliente cl ON ba.RUNCliente = cl.RUN
            LEFT JOIN estudiante e ON cl.RUN = e.RUN
            LEFT JOIN carrera c ON e.COD_CARRERA = c.COD_CARRERA
            GROUP BY c.Nombre
            ORDER BY total DESC";
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    private function obtenerEstadisticasPorMotivo() {
        $sql = "
            SELECT 
                COALESCE(ba.Motivo, 'Sin Motivo') as nombre,
                COUNT(ba.ID) as total
            FROM bloque b
            INNER JOIN bloqueatencion ba ON b.ID = ba.ID
            GROUP BY ba.Motivo
            ORDER BY total DESC";
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    private function obtenerEstadisticasPorEstado() {
        $sql = "
            SELECT 
                COALESCE(ba.Estado, 'Sin Estado') as nombre,
                COUNT(ba.ID) as total
            FROM bloque b
            INNER JOIN bloqueatencion ba ON b.ID = ba.ID
            GROUP BY ba.Estado
            ORDER BY total DESC";
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}
