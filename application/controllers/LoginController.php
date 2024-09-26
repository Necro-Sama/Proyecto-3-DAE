<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LoginController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("UserModel");
        $this->load->model("BloquesReservadosModel");
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules("correo", "Correo", "required|valid_email");
        $this->form_validation->set_rules("contraseña", "Contraseña", "required");
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
    }
    public function index() {
        $this->load->view('LoginView');
    }
    public function auth() {
        if ($this->form_validation->run() == FALSE) {
            $this->load->view("LoginView");
        } else {
            $correo = $this->input->post("correo");
            $contraseña = $this->input->post("contraseña");
            $tipo_usuario = $this->input->post("user_type");
            $usuario = $this->UserModel->login($correo, $contraseña, $tipo_usuario);
            if ($usuario) {
                switch ($usuario->user_type) {
                    case 'student':
                        
                        $this->load->view("StudentView");
                        break;

                    case 'admin':
                        $this->load->view("AdminView");
                        break;

                    case 'ta':
                        $this->load->view("TaView");
                        break;
                    
                    default:
                        # code...
                        break;
                }
            } else {
                $this->load->view("LoginView", array("invalid_user" => "Cuenta o correo incorrectos"));
            }
        }
    }

    // public function authenticate() {
    //     // Reglas de validación para el email y contraseña
    //     $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
    //     $this->form_validation->set_rules('password', 'Password', 'required');
        
    //     if ($this->form_validation->run() == FALSE) {
    //         // Si la validación falla, recargar la vista
    //         $this->load->view('login_view');
    //     } else {
    //         // Obtener los datos del formulario
    //         $email = $this->input->post('email');
    //         $password = $this->input->post('password');
            
    //         // Verificar si el usuario existe
    //         $user = $this->User_model->login($email, $password);

    //         if ($user) {
    //             // Usuario autenticado, redirigir al dashboard o página de éxito
    //             redirect('dashboard');
    //         } else {
    //             // Mostrar un mensaje de error si el login falla
    //             $data['error'] = 'Email o contraseña incorrecta';
    //             $this->load->view('login_view', $data);
    //         }
    //     }
    // }

    // public function no_student() {
    //     // Redirigir a la página para no estudiantes
    //     redirect('no_student_dashboard');
    // }
}
