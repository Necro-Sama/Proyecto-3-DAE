<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class UserController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->model("UserModel");
        $this->load->model("BloqueModel");
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules("correo", "Correo", "required|valid_email");
        $this->form_validation->set_rules("contraseña", "Contraseña", "required");
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
    }
    public function index() {
        if ($this->check_logged_in()) {
            redirect("/usuarios/home");
        } else {
            redirect("/usuarios/login");
        }
    }
    public function googletest() {
        $this->load->view("googletest");
    }
    public function login() {
        // echo password_hash("random", PASSWORD_DEFAULT);
        // return;
        if ($this->check_logged_in()) {
            redirect("/usuarios/home");
        }
        $this->load->view('LoginView');
    }
    public function logout() {
        $this->UserModel->logout($this->session->token);
        redirect("/usuarios/login");
    }
    public function auth() {
        if ($this->check_logged_in()) {
            redirect("/usuarios/home");
        }
        if ($this->form_validation->run() == FALSE) {
            $this->session->form_error = validation_errors();
            redirect("/usuarios/login");
        } else {
            $correo = $this->input->post("correo");
            $contraseña = $this->input->post("contraseña");
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
        $RUN_usuario = $this->check_logged_in();
        if (!$RUN_usuario) {
            session_destroy();
            redirect("/usuarios/login");
        }
        // Checkear si es trabajador social
        $trabajador_social = $this->UserModel->get_trabajador_social($RUN_usuario);
        // print_r($trabajador_social);
        if ($trabajador_social) {
            $this->load->view("TSView");
        }
        // Checkear si es estudiante
        $estudiante = $this->UserModel->get_estudiante($RUN_usuario);
        if ($estudiante) {
            // $bloques_no_disponibles = $this->get_bloques_no_disponibles_carrera($estudiante->COD_CARRERA);
            $this->load->view("StudentView");
        }
        // Checkear si es no estudiante

        $no_estudiante = $this->UserModel->get_no_estudiante($RUN_usuario);
        if ($no_estudiante) {
            // $bloques_no_disponibles = $this->get_bloques_no_disponibles_carrera($estudiante->COD_CARRERA);
            // $this->load->view("NotStudentView");
            echo "No estudiante";
            $this->load->view("StudentView");
            $this->load->view("StudentAgendarView");
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

    }
    public function agendar() {
        $this->load->view("StudentAgendarView");
        
        //$fecha = $this->input->post("fecha");
        //$num_bloque = $this->input->post("num_bloque");
        //$motivo = $this->input->post("motivo");
        //$usuario = $this->check_logged_in();
        //if ($usuario) {
        //    $result = $this->BloquesReservadosModel->agendar($usuario, $fecha, $num_bloque, $motivo);
        //    if (!$result) {
        //        $this->session->agendar_error = "Error a la hora de agendar la hora.";
        //        $this->session->mark_as_flash("agendar_error");
        //    }
        //    redirect("/usuarios/home");
        //} else {
        //    redirect("/usuarios/login");
        //}
    }
    public function gestion_ts($RUN_usuario)
    {
        return $this->UserModel->get_admin($RUN_usuario);
    }
    public function licencia_ts($RUN_usuario){
        $this->load->view("LicenciaView");
    }
    public function cargar_vista()
    {
        $RUN_usuario = $this->session->userdata('RUN');
        $gestion_ts = $this->UserModel->get_admin($RUN_usuario); 
    
        $data['gestion_ts'] = $gestion_ts;
        $this->load->view('navbar', $data); 
    }
    
    
    public function logged_in($token) {
        if ($token) {
            return $this->UserModel->login_token($token);
        }
    }
    public function check_logged_in() {
        // $this->session->token;
        // Check logging normal (token de la base de datos)
        // ECHO " NORMAL TOKEN: ".$this->session->token;
        if ($this->session->token) {
            return $this->UserModel->login_token($this->session->token);
        }
        // Check google logging
        // Get $id_token via HTTPS POST.
        $cred = $this->input->post("credential");

        $g_id_token = $cred ? $cred : $this->session->google_token;
        // echo " GOOGLE TOKEN: ".$g_id_token;
        if (!$g_id_token) {
            return;
        }
        $g_client = $this->UserModel->check_google_logged_in($g_id_token);
        if ($g_client) {
            $this->session->google_token = $g_id_token;
            return $g_client;
        }
    }
}