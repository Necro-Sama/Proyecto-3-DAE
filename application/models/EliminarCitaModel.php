<?php
defined("BASEPATH") or exit("No direct script access allowed");

class EliminarCitaModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Método para obtener todas las citas
    public function obtenerCitas()
    {
        // Consulta directa sin alias para el campo estado
        $this->db->select('
            ba.ID,
            ba.RUNCliente,
            ba.estado,
            ba.motivo,
            b.FechaInicio,
            b.FechaTermino,
            b.RUNTS
        ');
        $this->db->from('bloqueatencion as ba');
        $this->db->join('bloque as b', 'ba.ID = b.ID', 'left');
        
        $query = $this->db->get();
        
        // Debug para verificar la consulta y los resultados
        log_message('debug', 'SQL Query: ' . $this->db->last_query());
        $resultados = $query->result_array();
        log_message('debug', 'Resultados de la consulta: ' . json_encode($resultados));
        
        return $resultados;
    }

    // Método para cambiar el estado de una cita
    public function cambiarEstadoCita($idCita, $runCliente)
    {
        // Debug: Inicio de la función
        log_message('debug', '=== INICIO cambiarEstadoCita() ===');
        log_message('debug', 'Parámetros recibidos - ID: ' . $idCita . ', RUN: ' . $runCliente);

        $this->db->trans_start();

        // Verificar existencia de la cita
        $this->db->where('ID', $idCita);
        $this->db->where('RUNCliente', $runCliente);
        $existe = $this->db->get('bloqueatencion')->row();

        if (!$existe) {
            log_message('error', 'No se encontró la cita para cancelar');
            $this->db->trans_complete();
            return false;
        }

        // Usar el valor exacto del ENUM: 'Cancelado'
        $datos = array('Estado' => 'Cancelado');
        log_message('debug', 'Intentando actualizar con estado: Cancelado');

        $this->db->where('ID', $idCita);
        $this->db->where('RUNCliente', $runCliente);
        $actualizado = $this->db->update('bloqueatencion', $datos);

        // Debug del resultado
        log_message('debug', 'Query de actualización: ' . $this->db->last_query());
        log_message('debug', 'Filas afectadas: ' . $this->db->affected_rows());

        if (!$actualizado) {
            log_message('error', 'Error en la actualización: ' . json_encode($this->db->error()));
        }

        $this->db->trans_complete();

        // Verificación final
        $exitoso = $this->db->trans_status();
        if ($exitoso) {
            log_message('debug', 'Actualización exitosa con estado: Cancelado');
        } else {
            log_message('error', 'Falló la transacción');
        }

        return $exitoso;
    }

    public function marcarComoAtendido($idCita, $runCliente)
    {
        $this->db->trans_start();

        // Verificar existencia de la cita
        $this->db->where('ID', $idCita);
        $this->db->where('RUNCliente', $runCliente);
        $existe = $this->db->get('bloqueatencion')->row();

        if (!$existe) {
            log_message('error', 'No se encontró la cita');
            $this->db->trans_complete();
            return false;
        }

        // Actualizar estado a 'Atendido'
        $datos = array('Estado' => 'Atendido');
        $this->db->where('ID', $idCita);
        $this->db->where('RUNCliente', $runCliente);
        $actualizado = $this->db->update('bloqueatencion', $datos);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }
} 