<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TrabajadorSocialController extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('TrabajadorSocialModel');
        $this->load->database();
    }

    public function index() {
        $data['trabajadores'] = $this->TrabajadorSocialModel->obtenerTS();
        $this->load->helper('url');
        $this->load->view('gestor_ts', $data);
        $this->load->view('footer');
    }

    public function agregar() {
        // Obtener datos de la persona desde el formulario
        $personaData = [
            'RUN' => $this->input->post('RUN'),
            'NOMBRE_PER' => $this->input->post('Nombre'),
            'APELLID_PER' => $this->input->post('Apellido'),
            'CORREO' => $this->input->post('Correo'),
            'TELEFONO' => $this->input->post('Telefono')
        ];

        // Insertar primero en la tabla Persona
        $this->TrabajadorSocialModel->agregarPersona($personaData);

        // Insertar en la tabla TS usando el mismo RUN
        $tsData = [
            'RUN' => $personaData['RUN']
            // Puedes añadir más datos específicos de TS si los tienes
        ];
        $this->TrabajadorSocialModel->agregarTS($tsData);

        // Redirigir o mostrar un mensaje de éxito
        redirect('gestor_ts');
    }

    public function editar($id) {
        // Obtener datos de persona y TS desde el formulario
        $personaData = [
            'NOMBRE_PER' => $this->input->post('Nombre'),
            'APELLID_PER' => $this->input->post('Apellido'),
            'CORREO' => $this->input->post('Correo'),
            'TELEFONO' => $this->input->post('Telefono')
        ];

        $tsData = [
            // Datos específicos de TS si hay más atributos
        ];

        // Obtener el RUN del TS a editar
        $ts = $this->TrabajadorSocialModel->obtenerTSPorID($id);
        $run = $ts['RUN'];

        // Editar primero los datos de Persona
        $this->TrabajadorSocialModel->editarPersona($run, $personaData);

        // Luego, editar los datos en la tabla TS
        $this->TrabajadorSocialModel->editarTS($id, $tsData);

        // Redirigir o mostrar un mensaje de éxito
        redirect('gestor_ts');
    }

    public function eliminar($id) {
        $this->TrabajadorSocialModel->eliminarTS($id);
        redirect('gestor_ts');
    }
}
