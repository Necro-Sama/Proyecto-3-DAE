<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReagendarModel extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function obtenerCitaPorId($idCita) {
        $this->db->select('b.*, ba.Motivo, ba.Estado, ba.RUNCliente, 
                          p.Nombre as NombreTS, p.Apellido as ApellidoTS')
            ->from('bloque b')
            ->join('bloqueatencion ba', 'b.ID = ba.ID')
            ->join('persona p', 'b.RUNTS = p.RUN')
            ->where('b.ID', $idCita);
        
        $query = $this->db->get();
        
        if ($query->num_rows() === 0) {
            return null;
        }
        
        return $query->row_array();
    }

    public function obtenerTipoUsuario($run) {
        $query = $this->db->select('Tipo')
                         ->from('persona')
                         ->where('RUN', $run)
                         ->get();
        
        if ($query->num_rows() === 0) {
            return null;
        }
        
        return $query->row()->Tipo;
    }

    public function procesarReagendamiento($idCitaAnterior, $nuevaFechaInicio, $nuevaFechaFin, $motivo, $runCliente) {
        $this->db->trans_begin();

        try {
            // Verificar disponibilidad del nuevo horario
            if (!$this->verificarDisponibilidad($nuevaFechaInicio, $nuevaFechaFin)) {
                throw new Exception('El horario seleccionado no está disponible');
            }

            // Crear nueva cita
            $nuevoId = $this->crearNuevaCita($nuevaFechaInicio, $nuevaFechaFin, $motivo, $runCliente);

            // Eliminar cita anterior
            $this->eliminarCitaAnterior($idCitaAnterior);

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Error en la transacción');
            }

            $this->db->trans_commit();
            return true;

        } catch (Exception $e) {
            $this->db->trans_rollback();
            log_message('error', 'Error en reagendamiento: ' . $e->getMessage());
            throw $e;
        }
    }

    private function verificarDisponibilidad($fechaInicio, $fechaFin) {
        $this->db->select('*')
            ->from('bloque')
            ->where('FechaInicio <', $fechaFin)
            ->where('FechaTermino >', $fechaInicio);
        
        return $this->db->get()->num_rows() === 0;
    }

    private function crearNuevaCita($fechaInicio, $fechaFin, $motivo, $runCliente) {
        // Implementar lógica para crear nueva cita
        // Similar a la función agendar_cita del BloqueModel
    }

    private function eliminarCitaAnterior($idCita) {
        $this->db->where('ID', $idCita)
            ->delete('bloqueatencion');
            
        $this->db->where('ID', $idCita)
            ->delete('bloque');
    }
}