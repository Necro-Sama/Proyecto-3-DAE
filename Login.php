<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');  // Cargar el modelo de usuarios
        $this->load->library('form_validation');  // Cargar la librería de validación de formularios
    }

    public function index() {
        // Cargar la vista del formulario de login
        $this->load->view('login_view');
    }

    public function authenticate() {
        // Reglas de validación para el email y contraseña
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            // Si la validación falla, recargar la vista
            $this->load->view('login_view');
        } else {
            // Obtener los datos del formulario
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            
            // Verificar si el usuario existe
            $user = $this->User_model->login($email, $password);

            if ($user) {
                // Usuario autenticado, redirigir al dashboard o página de éxito
                redirect('dashboard');
            } else {
                // Mostrar un mensaje de error si el login falla
                $data['error'] = 'Email o contraseña incorrecta';
                $this->load->view('login_view', $data);
            }
        }
    }

    public function no_student() {
        // Redirigir a la página para no estudiantes
        redirect('no_student_dashboard');
    }
}
