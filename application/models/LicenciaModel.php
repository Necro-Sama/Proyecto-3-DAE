<?php
require_once FCPATH.'google/vendor/autoload.php';

class Trabajadores_model extends CI_Model {
    // Seccion licencias
    public function obtenerTrabajadoresSociales() {
        $query = $this->db->query('
        SELECT p.Nombre,p.Apellido,p.RUN FROM persona p
        join trabajadorsocial t
        on (p.RUN = t.RUN)
        '); 
        $result = $query->result_array();
        return $result;
    }

    public function guardarLicencia($run, $fecha_inicio, $fecha_termino) {
        $data = [
            'RUN' => $run,
            'FECHA_INI' => $fecha_inicio,
            'FECHA_TER' => $fecha_termino,
        ];
        if($this->ConsultaEstado($run)){
            $this->db->query('
            UPDATE persona  set Activo = 0 where RUN = ?
            ', array($run));
        }
        return $this->db->insert('Licencia', $data);
    }
    public function ConsultaEstado($run){
    
        return $this->db->query('
        SELECT Activo from persona
        where RUN =?
        ',array($run));
    }

}