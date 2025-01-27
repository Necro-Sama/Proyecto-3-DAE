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
            // Primero verificamos si el bloque ya está bloqueado
            $this->db->where('fechainicio', $data['fechainicio']);
            $this->db->where('fechafinal', $data['fechafinal']);
            $this->db->where('RUN', $data['RUN']);
            $bloque_existente = $this->db->get('bloquebloqueado')->num_rows() > 0;

            if ($bloque_existente) {
                log_message('error', 'El bloque ya está bloqueado para este horario');
                return 'bloqueado'; // Retornamos un estado específico
            }

            $this->db->trans_start();

            // Calcular FechaInicioSemana
            $fechaInicio = new DateTime($data['fechainicio']);
            $diaSemana = $fechaInicio->format('w');
            $diasHastaLunes = $diaSemana == 0 ? 6 : $diaSemana - 1;
            $fechaInicio->sub(new DateInterval("P{$diasHastaLunes}D"));
            $fechaInicioSemana = $fechaInicio->format('Y-m-d') . ' 03:00:00';

            // Verificar si existe la semana en calendariosemanal
            $this->db->where('FechaInicioSemana', $fechaInicioSemana);
            $this->db->where('RUNTS', $data['RUN']);
            $existe_semana = $this->db->get('calendariosemanal')->num_rows() > 0;

            // Si no existe la semana, la creamos
            if (!$existe_semana) {
                $semana_data = array(
                    'FechaInicioSemana' => $fechaInicioSemana,
                    'RUNTS' => $data['RUN']
                );
                $this->db->insert('calendariosemanal', $semana_data);
                log_message('debug', 'Nueva semana insertada en calendariosemanal');
            }

            // Ahora insertamos en la tabla bloque
            $bloque_data = array(
                'ID' => $data['ID'],
                'FechaInicio' => $data['fechainicio'],
                'FechaTermino' => $data['fechafinal'],
                'FechaInicioSemana' => $fechaInicioSemana,
                'RUNTS' => $data['RUN']
            );

            // Verificar si el bloque ya existe
            $this->db->where('ID', $data['ID']);
            $existe_bloque = $this->db->get('bloque')->num_rows() > 0;

            if (!$existe_bloque) {
                $result_bloque = $this->db->insert('bloque', $bloque_data);
                log_message('debug', 'Bloque insertado en tabla bloque: ' . ($result_bloque ? 'true' : 'false'));
                
                if (!$result_bloque) {
                    log_message('error', 'Error al insertar en tabla bloque: ' . print_r($this->db->error(), true));
                    return false;
                }
            }

            // Luego insertamos en bloquebloqueado
            $bloqueo_data = array(
                'ID' => $data['ID'],
                'fechainicio' => $data['fechainicio'],
                'fechafinal' => $data['fechafinal'],
                'RUN' => $data['RUN']
            );

            $result_bloqueado = $this->db->insert('bloquebloqueado', $bloqueo_data);
            log_message('debug', 'Bloque insertado en tabla bloquebloqueado: ' . ($result_bloqueado ? 'true' : 'false'));
            
            if (!$result_bloqueado) {
                log_message('error', 'Error al insertar en tabla bloquebloqueado: ' . print_r($this->db->error(), true));
                return false;
            }

            $this->db->trans_complete();

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
    
    public function obtenerTrabajadoresSociales() {
        $this->db->select('p.RUN, p.Nombre, p.Apellido');
        $this->db->from('persona p');
        $this->db->join('trabajadorsocial ts', 'p.RUN = ts.RUN');
        $this->db->where('p.Activo', 1); // Solo trabajadores sociales activos
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }

    public function obtenerTrabajadorSocialPorRUN($run) {
        $this->db->select('p.RUN, p.Nombre, p.Apellido');
        $this->db->from('persona p');
        $this->db->join('trabajadorsocial ts', 'p.RUN = ts.RUN');
        $this->db->where('p.RUN', $run);
        $this->db->where('p.Activo', 1);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            return array($query->row_array()); // Devolver como array para mantener consistencia
        }
        return array();
    }
}
?>
