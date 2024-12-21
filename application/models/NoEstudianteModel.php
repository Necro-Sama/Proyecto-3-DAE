<?php
require_once FCPATH.'google/vendor/autoload.php';

Class NoEstudianteModel extends CI_Model{
    
    // Insertar datos en la tabla `cliente`
    public function insertCliente($data)
    {
        return $this->db->insert('cliente', $data);
    }

    // Insertar datos en la tabla `noestudiante`
    public function insertNoEstudiante($data)
    {
        return $this->db->insert('noestudiante', $data);
    }
}
    