<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TrabajadorSocialModel extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Método para obtener todos los trabajadores sociales con su información personal
    public function obtenerTrabajadoresSociales() {
        $this->db->select('trabajadorsocial.RUN, persona.Nombre, persona.Apellido, persona.Correo, persona.Telefono');
        $this->db->from('trabajadorsocial');
        $this->db->join('funcionario', 'trabajadorsocial.RUN = funcionario.RUN', 'inner');
        $this->db->join('persona', 'funcionario.RUN = persona.RUN', 'inner');
        return $this->db->get()->result_array();
    }

    // Método para agregar un trabajador social
    public function agregarTrabajadorSocial($datosPersona, $datosTrabajadorSocial) {
        // Primero, insertar en la tabla `persona`
        $this->db->insert('persona', $datosPersona);

        // Luego, insertar en la tabla `funcionario`
        $datosFuncionario = ['RUN' => $datosPersona['RUN']];
        $this->db->insert('funcionario', $datosFuncionario);

        // Finalmente, insertar en la tabla `trabajadorsocial`
        $datosTrabajadorSocial['RUN'] = $datosPersona['RUN'];
        return $this->db->insert('trabajadorsocial', $datosTrabajadorSocial);
    }

    // Método para actualizar un trabajador social
    public function actualizarTrabajadorSocial($RUN, $datosPersona) {
        // Actualizar la tabla `persona`
        $this->db->where('RUN', $RUN);
        $this->db->update('persona', $datosPersona);
    }

    // Método para eliminar un trabajador social
    public function eliminarTrabajadorSocial($RUN) {
        // Eliminar de la tabla `trabajadorsocial`
        $this->db->where('RUN', $RUN);
        $this->db->delete('trabajadorsocial');

        // Eliminar de la tabla `funcionario`
        $this->db->where('RUN', $RUN);
        $this->db->delete('funcionario');

        // Finalmente, eliminar de la tabla `persona`
        $this->db->where('RUN', $RUN);
        $this->db->delete('persona');
    }
}
?>
