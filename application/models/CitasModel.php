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

    public function verificarBloque($dia, $id, $fechaInicio)
    {
        $this->db->where('id', $id);
        $this->db->where('fechaInicio', $fechaInicio);
        $query = $this->db->get('bloquebloqueado');
        return $query->num_rows() > 0;
    }

    // Insertar un nuevo bloqueo
    public function bloquearHorario($runUsuario, $dia, $id, $fechaInicio, $fechaFinal)
    {
        $data = [
            'RUN' => $runUsuario,
            'id' => $id,
            'fechaInicio' => $fechaInicio,
            'fechaFinal' => $fechaFinal,
        ];
        $this->db->insert('bloquebloqueado', $data);
    }

    public function seleccionarfecha(){
        //la idea principal es enviarlo a la agenda para que seleccione otro dia de las 3 semanas 
        //de esta manera cambiamos el boton de agendar por uno que diga reagendar de la misma manera que bloquear con un if
        //traspasamos una variable para que pueda reconocer el proceso
        //una vez se seleccione llamara a la funcion reagendar para agedar y eliminar la otra pasando los datos de la eliminacion
        //para la funcion eliminar cita y reusar funciones
        
    }
    public function reagendar(){
        //llamara a eliminar cita para borrar la anterior y pasara a tomar la nueva una vez terminado enviara un mensaje de reagendado con exito.

    }
    
}
?>
