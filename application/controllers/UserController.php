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
        if ($this->logged_in($this->session->token)) {
            redirect("/usuarios/home");
        } else {
            redirect("/usuarios/login");
        }
    }
    public function googletest() {
        $this->load->view("googletest");
    }
    public function login() {
        if ($this->logged_in($this->session->token)) {
            redirect("/usuarios/home");
        }
        session_destroy();
        $this->load->view('LoginView');
    }
    public function logout() {
        $this->UserModel->logout($this->session->token);
        redirect("/usuarios/login");
    }
    public function auth() {
        if ($this->logged_in($this->session->token)) {
            redirect("/usuarios/home");
        }
        if ($this->form_validation->run() == FALSE) {
            $this->session->form_error = validation_errors();
            redirect("/usuarios/login");
        } else {
            $correo = $this->input->post("correo");
            $contraseña = $this->input->post("contraseña");
            // echo password_hash($contraseña, PASSWORD_DEFAULT);
            // return;
            $token = $this->UserModel->login($correo, $contraseña);
            if ($token) {
                $this->session->token = $token;
                redirect("/usuarios/home");
            } else {
                redirect("/usuarios/login");
            }
        }
    }
    public function get_bloques_no_disponibles_carrera($carrera) {
        return $this->BloquesReservadosModel->get_bloques_no_disponibles_carrera($carrera);
    }
    public function home() {
        // $this->load->view("StudentView", array());
        $usuario = $this->logged_in($this->session->token);
        if ($usuario) {
            // print_r($usuario);
            // Checkear si es trabajador social
            $trabajador_social = $this->UserModel->get_trabajador_social($usuario->RUN);
            print_r($trabajador_social);
            if ($trabajador_social) {
                $this->load->view("TSView");
            }
            // Checkear si es estudiante
            $estudiante = $this->UserModel->get_estudiante($usuario->RUN);
            print_r($estudiante);
            if ($estudiante) {
                $this->load->view("StudentView");
            }
            // $user_type = $usuario->user_type;
            // $carrera = $usuario->carrera;

            // switch ($user_type) {
            //     case 'student':
            //         $bloques_no_disponibles = $this->get_bloques_no_disponibles_carrera($carrera);
            //         $this->load->view(
            //             "StudentView", array(
            //                 "bloques_no_disponibles" => $bloques_no_disponibles,
            //                 "carrera" => $carrera));
            //         break;

            //     case 'admin':
            //         $this->load->view("AdminView");
            //         break;

            //     case 'ts':
            //         $this->load->view("TSView");
            //         break;
                
            //     default:
                    
            //         break;
            // }
        } else {
            redirect("/usuarios/login");
        }
    }
    public function agendar() {
        $fecha = $this->input->post("fecha");
        $num_bloque = $this->input->post("num_bloque");
        $motivo = $this->input->post("motivo");
        $usuario = $this->logged_in($this->session->token);
        if ($usuario) {
            $result = $this->BloquesReservadosModel->agendar($usuario, $fecha, $num_bloque, $motivo);
            if (!$result) {
                $this->session->agendar_error = "Error a la hora de agendar la hora.";
                $this->session->mark_as_flash("agendar_error");
            }
            redirect("/usuarios/home");
        } else {
            redirect("/usuarios/login");
        }
    }
    public function logged_in($token) {
        if ($token) {
            return $this->UserModel->login_token($token);
        }
    }
}
