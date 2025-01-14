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
    public function bloquearBloque($id, $fechainicio, $fechafinal, $RUN) {
        // Verificar si ya existe un bloqueo con la misma fecha inicial
        $query = $this->db->get_where('bloquebloqueado', ['fechainicio' => $fechainicio]);
        
        if ($query->num_rows() > 0) {
            echo 'Fecha ya Bloqueada';
        } else {
            // Datos a insertar en la tabla
            $data = [
                'ID' => $id,
                'fechainicio' => $fechainicio,
                'fechafinal' => $fechafinal,
                'RUN' => $RUN
            ];
    
            // Insertar el nuevo registro
            $this->db->insert('bloquebloqueado', $data);
    
            if ($this->db->affected_rows() > 0) {
                echo 'Bloqueo creado exitosamente';
            } else {
                echo 'Error al bloquear el bloque';
            }
        }
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
