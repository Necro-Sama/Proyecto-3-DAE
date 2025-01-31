<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EstadisticasModel extends CI_Model {
    
    public function obtenerEstadisticas($fecha_inicio, $fecha_fin = null) {
        if ($fecha_fin === null) {
            // Si solo se proporciona un año
            $fecha_inicio = $fecha_inicio . '-01-01';
            $fecha_fin = $fecha_inicio . '-12-31';
        }

        // Estadísticas por carrera
        $this->db->select('carrera.nombre_carrera, COUNT(*) as total');
        $this->db->from('citas');
        $this->db->join('usuarios', 'usuarios.rut = citas.rut_estudiante');
        $this->db->join('carrera', 'carrera.id_carrera = usuarios.id_carrera');
        $this->db->where('citas.fecha_inicio >=', $fecha_inicio);
        $this->db->where('citas.fecha_inicio <=', $fecha_fin);
        $this->db->group_by('carrera.nombre_carrera');
        $por_carrera = $this->db->get()->result();

        // Citas canceladas
        $this->db->where('estado', 'cancelada');
        $this->db->where('fecha_inicio >=', $fecha_inicio);
        $this->db->where('fecha_inicio <=', $fecha_fin);
        $canceladas = $this->db->count_all_results('citas');

        // Temas recurrentes
        $this->db->select('bloqueatencion.estado, COUNT(*) as total');
        $this->db->from('bloqueatencion');
        $this->db->join('citas', 'citas.id_cita = bloqueatencion.id_cita');
        $this->db->where('citas.fecha_inicio >=', $fecha_inicio);
        $this->db->where('citas.fecha_inicio <=', $fecha_fin);
        $this->db->group_by('bloqueatencion.estado');
        $temas = $this->db->get()->result();

        return [
            'por_carrera' => $por_carrera,
            'canceladas' => $canceladas,
            'temas' => $temas
        ];
    }
}
