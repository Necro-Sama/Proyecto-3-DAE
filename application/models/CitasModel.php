<?php
defined("BASEPATH") or exit("No direct script access allowed");

class CitasModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Método para obtener todas las citas
    public function obtenerCitas()
    {
        $this->db->select('*');
        $this->db->from('bloqueatencion');
        $this->db->join('bloque', 'BloqueAtencion.ID = Bloque.ID');
        $query = $this->db->get();
        return $query->result_array(); // Devuelve las citas como un array
    }

    // Método para eliminar una cita
    public function eliminarCita($idCita, $runCliente)
    {
        $this->db->trans_start(); // Inicia una transacción

        // Verificar si la cita existe para este cliente
        $this->db->where('ID', $idCita);
        $this->db->where('RUNCliente', $runCliente);
        $existe = $this->db->get('bloqueatencion')->row();

        if (!$existe) {
            $this->db->trans_complete();
            return false; // Si no existe la cita, retorna false
        }

        // Eliminar de ambas tablas
        $this->db->where('ID', $idCita);
        $this->db->delete('bloqueatencion');

        $this->db->where('ID', $idCita);
        $this->db->delete('bloque');

        $this->db->trans_complete(); // Finaliza la transacción

        return $this->db->trans_status(); // Retorna el estado de la transacción
    }

    public function verificar_bloque($id_bloque)
    {
        // Verificar si el bloque está reservado en la tabla bloqueatencion
        
        $query_atencion =$this->db->query('
            SELECT ID FROM bloqueantecion
            where ID = ??
        ',$id_bloque);

        if ($query_atencion->num_rows() > 0) {
            return 'Reservado'; // Bloque reservado
        }

        // Verificar si el bloque está bloqueado en la tabla bloquebloqueado
        $query_bloqueado =$this->db->query('
            SELECT ID FROM bloquebloqueado
            where ID = ??
        ',$id_bloque);
        if ($query_bloqueado->num_rows() > 0) {
            return 'Bloqueado'; // Bloque bloqueado
        }

        // Si no está ni reservado ni bloqueado
        return ['estado' => 'Disponible']; // Bloque disponible
    }
}
?>
