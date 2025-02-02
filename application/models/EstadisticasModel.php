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

        // Actualizar estados antes de obtener estadísticas
        $this->actualizarEstadosAutomaticamente();

        // Obtener todas las estadísticas
        $estadisticas = [
            'estados' => $this->obtenerEstadisticasPorEstado($fecha_inicio, $fecha_fin),
            'por_carrera' => $this->obtenerEstadisticasPorCarrera($fecha_inicio, $fecha_fin),
            'por_motivo' => $this->obtenerEstadisticasPorMotivo($fecha_inicio, $fecha_fin)
        ];

        // Calcular el total de citas sumando los estados
        $total = 0;
        foreach ($estadisticas['estados'] as $estado) {
            $total += intval($estado['total']);
        }
        $estadisticas['total_citas'] = $total;

        return $estadisticas;
    }

    private function actualizarEstadosAutomaticamente() {
        $this->db->query("
            UPDATE bloqueatencion ba
            INNER JOIN bloque b ON ba.ID = b.ID
            SET ba.Estado = 'Ausente'
            WHERE b.FechaInicio < NOW() 
            AND ba.Estado = 'Reservado'
        ");
    }

    private function obtenerEstadisticasPorEstado($fecha_inicio, $fecha_fin) {
        // Consulta base para obtener los estados reales
        $query = $this->db->query("
            SELECT 
                ba.Estado,
                COUNT(*) as total
            FROM bloqueatencion ba
            INNER JOIN bloque b ON ba.ID = b.ID
            WHERE b.FechaInicio BETWEEN ? AND ?
            GROUP BY ba.Estado
            ORDER BY ba.Estado
        ", array($fecha_inicio . ' 00:00:00', $fecha_fin . ' 23:59:59'));

        // Crear array con todos los estados posibles
        $estados = [
            'Reservado' => 0,
            'Atendido' => 0,
            'Cancelado' => 0,
            'Ausente' => 0
        ];
        
        // Llenar con los datos reales
        foreach ($query->result() as $row) {
            if (isset($estados[$row->Estado])) {
                $estados[$row->Estado] = intval($row->total);
            }
        }

        // Convertir a formato de array para la vista
        $resultado = [];
        foreach ($estados as $estado => $total) {
            $resultado[] = [
                'Estado' => $estado,
                'total' => strval($total)
            ];
        }

        return $resultado;
    }

    private function obtenerEstadisticasPorCarrera($fecha_inicio, $fecha_fin) {
        $query = $this->db->query("
            SELECT 
                COALESCE(c.Nombre, 'Sin Carrera') as nombre_carrera,
                COUNT(*) as total
            FROM bloqueatencion ba
            INNER JOIN bloque b ON ba.ID = b.ID
            LEFT JOIN estudiante e ON ba.RUNCliente = e.RUN
            LEFT JOIN carrera c ON e.COD_CARRERA = c.COD_CARRERA
            WHERE b.FechaInicio BETWEEN ? AND ?
            GROUP BY c.Nombre
            ORDER BY total DESC
        ", array($fecha_inicio . ' 00:00:00', $fecha_fin . ' 23:59:59'));

        return $query->result_array();
    }

    private function obtenerEstadisticasPorMotivo($fecha_inicio, $fecha_fin) {
        $query = $this->db->query("
            SELECT 
                COALESCE(ba.Motivo, 'Sin Motivo') as Motivo,
                COUNT(*) as total
            FROM bloqueatencion ba
            INNER JOIN bloque b ON ba.ID = b.ID
            WHERE b.FechaInicio BETWEEN ? AND ?
            GROUP BY ba.Motivo
            ORDER BY total DESC
        ", array($fecha_inicio . ' 00:00:00', $fecha_fin . ' 23:59:59'));

        return $query->result_array();
    }
}
