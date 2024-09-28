<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("UserModel");
        $this->load->model("BloquesReservadosModel");
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('session');

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
                $this->session->user_type = $usuario->user_type;
                $this->session->carrera = $usuario->carrera;
                $this->session->mark_as_flash('user_type');
                $this->session->mark_as_flash('carrera');
                redirect("/usuarios/home");

            } else {
                $this->load->view("LoginView", array("invalid_user" => "Correo o contraseña incorrectos."));
            }
        }
    }
    public function get_bloques_no_disponibles_carrera($carrera) {
        return $this->BloquesReservadosModel->get_bloques_no_disponibles_carrera($carrera);
    }
    public function home() {
        $user_type = $this->session->user_type;
        $carrera = $this->session->carrera;
        switch ($user_type) {
            case 'student':
                $bloques_no_disponibles = $this->get_bloques_no_disponibles_carrera(
                    $carrera);
                $this->load->view(
                    "StudentView", array(
                        "bloques_no_disponibles" => $bloques_no_disponibles,
                        "carrera" => $carrera));
                break;

            case 'admin':
                $this->load->view("AdminView");
                break;

            case 'ts':
                $this->load->view("TSView");
                break;
            
            default:
                
                break;
        }
    }
}
