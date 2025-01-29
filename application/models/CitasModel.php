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

    
    public function insertarBloqueBloqueado($data) {
        try {
            $this->db->trans_begin();

            // Insertar en la tabla bloquebloqueado
            $dataBloque = [
                'ID' => $data['ID'],
                'fechainicio' => $data['fechainicio'],
                'fechafinal' => $data['fechafinal'],
                'RUN' => $data['RUN']
            ];

            $resultado = $this->db->insert('bloquebloqueado', $dataBloque);
            
            if (!$resultado) {
                $this->db->trans_rollback();
                log_message('error', 'Error al insertar en tabla bloquebloqueado: ' . $this->db->error()['message']);
                return false;
            }

            $this->db->trans_commit();
            return true;

        } catch (Exception $e) {
            $this->db->trans_rollback();
            log_message('error', 'Error en insertarBloqueBloqueado: ' . $e->getMessage());
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

    public function verificarDisponibilidadBloque($fechainicio, $rut_trabajador) {
        try {
            // Log para debug
            log_message('debug', 'Verificando bloque - Fecha: ' . $fechainicio . ' RUT: ' . $rut_trabajador);

            // Consulta para verificar si existe el bloque
            $this->db->where('FechaInicio', $fechainicio);
            $this->db->where('RUNTS', $rut_trabajador);
            $query = $this->db->get('bloque');

            if ($query === FALSE) {
                log_message('error', 'Error en consulta SQL: ' . $this->db->error()['message']);
                throw new Exception('Error al consultar la base de datos');
            }

            $existe = ($query->num_rows() > 0);
            
            // Log del resultado
            log_message('debug', 'Bloque ' . ($existe ? 'existe' : 'no existe') . ' para fecha: ' . $fechainicio);

            return !$existe;

        } catch (Exception $e) {
            log_message('error', 'Error en verificarDisponibilidadBloque: ' . $e->getMessage());
            throw $e;
        }
    }

    public function obtenerBloquesBloqueados($fecha, $runts) {
        $this->db->select('b.*, ba.Estado')
            ->from('bloque b')
            ->join('bloqueatencion ba', 'b.ID = ba.ID')
            ->where('DATE(b.FechaInicio)', $fecha)
            ->where('b.RUNTS', $runts)
            ->where('ba.Estado', 'CanceladoTS');
        
        $query = $this->db->get();
        
        if ($query === FALSE) {
            throw new Exception('Error al consultar bloques bloqueados');
        }
        
        return $query->result();
    }

    public function desbloquearBloque($id, $runts) {
        try {
            $this->db->trans_begin();

            // Eliminar de bloqueatencion
            $this->db->where('ID', $id)
                ->where('Estado', 'CanceladoTS')
                ->delete('bloqueatencion');

            // Eliminar de bloque
            $this->db->where('ID', $id)
                ->where('RUNTS', $runts)
                ->delete('bloque');

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return false;
            }

            $this->db->trans_commit();
            return true;

        } catch (Exception $e) {
            $this->db->trans_rollback();
            log_message('error', 'Error en desbloquearBloque: ' . $e->getMessage());
            return false;
        }
    }

    public function insertarBloqueDiaCompleto($data) {
        try {
            $this->db->trans_begin();

            $dataBloque = [
                'ID' => $data['ID'],
                'fechainicio' => $data['fechainicio'],
                'fechafinal' => $data['fechafinal'],
                'RUN' => $data['RUN']
            ];

            $resultado = $this->db->insert('bloquebloqueado', $dataBloque);
            
            if (!$resultado) {
                $this->db->trans_rollback();
                log_message('error', 'Error al insertar bloque día completo: ' . $this->db->error()['message']);
                return false;
            }

            $this->db->trans_commit();
            return true;

        } catch (Exception $e) {
            $this->db->trans_rollback();
            log_message('error', 'Error en insertarBloqueDiaCompleto: ' . $e->getMessage());
            return false;
        }
    }

    public function insertarBloqueIndividual($data) {
        try {
            $this->db->trans_begin();

            $dataBloque = [
                'ID' => $data['ID'],
                'fechainicio' => $data['fechainicio'],
                'fechafinal' => $data['fechafinal'],
                'RUN' => $data['RUN']
            ];

            $resultado = $this->db->insert('bloquebloqueado', $dataBloque);
            
            if (!$resultado) {
                $this->db->trans_rollback();
                log_message('error', 'Error al insertar bloque individual: ' . $this->db->error()['message']);
                return false;
            }

            $this->db->trans_commit();
            return true;

        } catch (Exception $e) {
            $this->db->trans_rollback();
            log_message('error', 'Error en insertarBloqueIndividual: ' . $e->getMessage());
            return false;
        }
    }
}
?>
