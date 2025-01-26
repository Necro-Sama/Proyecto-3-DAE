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

    
    public function insertarBloqueBloqueado($data) 
    {
        log_message('debug', '=== Iniciando insertarBloqueBloqueado ===');
        log_message('debug', 'Datos recibidos: ' . print_r($data, true));

        try {
            $this->db->trans_start(); // Iniciamos transacción

            // Primero insertamos en la tabla bloque
            $bloque_data = array(
                'ID' => $data['ID'],
                'Estado' => 'Bloqueado'  // O el estado que corresponda
            );

            // Verificar si el bloque ya existe
            $this->db->where('ID', $data['ID']);
            $existe_bloque = $this->db->get('bloque')->num_rows() > 0;

            if (!$existe_bloque) {
                // Si no existe, lo insertamos
                $this->db->insert('bloque', $bloque_data);
                log_message('debug', 'Bloque insertado en tabla bloque');
            }

            // Luego insertamos en bloquebloqueado
            $bloqueo_data = array(
                'ID' => $data['ID'],
                'fechainicio' => $data['fechainicio'],
                'fechafinal' => $data['fechafinal'],
                'RUN' => $data['RUN']
            );

            // Verificar si ya existe el bloqueo
            $this->db->where('ID', $data['ID']);
            $this->db->where('fechainicio', $data['fechainicio']);
            $existe_bloqueo = $this->db->get('bloquebloqueado')->num_rows() > 0;

            if ($existe_bloqueo) {
                log_message('error', 'El bloque ya está bloqueado');
                $this->db->trans_rollback();
                return false;
            }

            $result = $this->db->insert('bloquebloqueado', $bloqueo_data);
            
            $this->db->trans_complete(); // Completamos transacción

            if ($this->db->trans_status() === FALSE) {
                log_message('error', 'Error en la transacción: ' . $this->db->error()['message']);
                return false;
            }

            log_message('debug', 'Inserción completada exitosamente');
            return true;

        } catch (Exception $e) {
            log_message('error', 'Exception en insertarBloqueBloqueado: ' . $e->getMessage());
            $this->db->trans_rollback();
            return false;
        }
    }
    public function seleccionarfecha(){
        //la idea principal es enviarlo a la agenda para que seleccione otro dia de las 3 semanas 
        //de esta manera cambiamos el boton de agendar por uno que diga reagendar de la misma manera que bloquear con un if
        //traspasamos una variable para que pueda reconocer el proceso
        //una vez se seleccione llamara a la funcion reagendar para agedar y eliminar la otra pasando los datos de la eliminacion
        //para la funcion eliminar cita y reusar funciones
        
    }
    
    
}
?>
