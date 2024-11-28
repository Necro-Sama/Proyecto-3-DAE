<?php
defined("BASEPATH") or exit("No direct script access allowed");

class UserController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library("session");
        $this->load->model("UserModel");
        $this->load->model("BloqueModel");
        $this->load->model("Trabajadores_model");
        $this->load->helper("url");
        $this->load->helper("form");
        $this->load->library("form_validation");

        $this->form_validation->set_rules(
            "correo",
            "Correo",
            "required|valid_email"
        );
        $this->form_validation->set_rules(
            "contraseña",
            "Contraseña",
            "required"
        );
        $this->form_validation->set_error_delimiters(
            '<div class="error">',
            "</div>"
        );
    }
    public function index()
    {
        if ($this->check_logged_in()) {
            redirect("/usuarios/home");
        } else {
            redirect("/usuarios/login");
        }
    }
    public function login()
    {
        // echo password_hash("random", PASSWORD_DEFAULT);
        // return;
        if ($this->check_logged_in()) {
            redirect("/usuarios/home");
        }
        $this->load->view("LoginView");
    }
    public function logout()
    {
        $this->UserModel->logout($this->session->token);
        redirect("/usuarios/login");
    }
    public function auth()
    {
        if ($this->check_logged_in()) {
            redirect("/usuarios/home");
        }
        if ($this->form_validation->run() == false) {
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
    public function get_bloques_no_disponibles_carrera($carrera)
    {
        return $this->BloquesReservadosModel->get_bloques_no_disponibles_carrera(
            $carrera
        );
    }
    public function home()
    {
        $RUN_usuario = $this->check_logged_in();
        if (!$RUN_usuario) {
            session_destroy();
            redirect("/usuarios/login");
        }
        // $this->load->view("navbar");
        // Checkear si es trabajador social
        $trabajador_social = $this->UserModel->get_trabajador_social(
            $RUN_usuario
        );
        $data['persona'] = $this->UserModel->getPersona($RUN_usuario);
        if ($this->UserModel->getEstudiante($RUN_usuario)) {
            $data['tipo'] = 'Estudiante';
            $data['detalle'] = $this->UserModel->getEstudiante($RUN_usuario);
        } elseif ($this->UserModel->getFuncionario($RUN_usuario)) {
            $data['tipo'] = 'Funcionario';
            $data['detalle'] = $this->UserModel->getFuncionario($RUN_usuario);
        } elseif ($this->UserModel->getAdministrador($RUN_usuario)) {
            $data['tipo'] = 'Administrador';
            $data['detalle'] = $this->UserModel->getAdministrador($RUN_usuario);
        }
        $this->load->view('HomeGlobal', $data);
        // print_r($trabajador_social);
        // if ($trabajador_social) {
        //     $this->load->view("TSView");
        // }
        // $administrador = $this->UserModel->get_admin($RUN_usuario);
        // // print_r($administrador);
        // if ($administrador) {
        //     $this->load->view("AdminView");
        // }
        // // Checkear si es estudiante
        // $estudiante = $this->UserModel->get_estudiante($RUN_usuario);
        // if ($estudiante) {
        //     // $bloques_no_disponibles = $this->get_bloques_no_disponibles_carrera($estudiante->COD_CARRERA);
        //     // $this->load->view("StudentView");
        //     // $this->load->view("StudentHome");
                    
        // 
        // $this->load->view('StudentHome', ['persona' => $persona]);
        //     // $this->BloqueModel->get_bloques_carrera($estudiante->COD_CARRERA);
        //     // $this->load->view("StudentAgendarView");
        // }
        // // Checkear si es no estudiante

        // $no_estudiante = $this->UserModel->get_no_estudiante($RUN_usuario);
        // if ($no_estudiante) {
        //     // $bloques_no_disponibles = $this->get_bloques_no_disponibles_carrera($estudiante->COD_CARRERA);
        //     // $this->load->view("NotStudentView");
        //     echo "No estudiante";
        //     $this->load->view("StudentView");
        //     $this->load->view("StudentAgendarView");
        // }
    }
    public function agendar()
    {
        $RUN_usuario = $this->check_logged_in();
        if (!$RUN_usuario) {
            session_destroy();
            redirect("/usuarios/login");
        }
        $this->load->view("StudentAgendarView", [
            "RUN_ESTUDIANTE" => $RUN_usuario,
        ]);
    }
    public function gestion_ts($RUN_usuario)
    {
        return $this->UserModel->get_admin($RUN_usuario);
    }

    public function cargar_vista()
    {
        $RUN_usuario = $this->session->userdata("RUN");
        $gestion_ts = $this->UserModel->get_admin($RUN_usuario);
        $data["gestion_ts"] = $gestion_ts;
        $this->load->view("navbar", $data);
        if (!$this->check_logged_in()) {
            $usuario = $this->check_logged_in();
            if (!$usuario) {
                session_destroy();
                redirect("/usuarios/login");
            }
            $this->load->view("navbar");
            $this->load->view("StudentAgendarView", [
                "RUN_ESTUDIANTE" => $usuario,
            ]);
        }
    }

    // Agendar solo en la semana actual para un Estudiante
    public function accion_agendar()
    {
        $usuario = $this->check_logged_in();
        if (!$usuario) {
            session_destroy();
            redirect("/usuarios/login");
        }
        $motivos = [
            "Gratuidad Mineduc",
            "Becas de arancel Mineduc",
            "Fondo Solidario de Crédito Universitario",
            "Beneficios Junaeb (BAES y Becas de mantención)",
            "Beca Fotocopia UTA",
            "Beca Alimentación UTA",
            "Beca Residencia UTA",
            "Beca Internado UTA",
            "Beca Ayuda Estudiantil UTA",
            "Beca PSU-PDT-PAES UTA",
            "Otro",
        ];
        // $fecha = $this->input->post("fecha");
        $num_bloque = $this->input->post("bloque_horario");
        $dia = $this->input->post("dia");
        $motivo = $this->input->post("motivo");

        if (!is_numeric($motivo)) {
            $this->session->agendar_error = "Por favor seleccione un Motivo.";
            $this->session->mark_as_flash("agendar_error");
            redirect("/usuarios/agendar");
        }
        try {
            $this->BloqueModel->agendar_estudiante(
                $usuario,
                $dia,
                $num_bloque,
                $motivos[$motivo + 0]
            );
        } catch (Exception $e) {
            $this->session->agendar_error = $e->getMessage();
            $this->session->mark_as_flash("agendar_error");
        }

        // echo $num_bloque . "," . $dia . "," . $motivos[$motivo - 1];
        redirect("/usuarios/agendar");
    }
    public function logged_in($token)
    {
        if ($token) {
            return $this->UserModel->login_token($token);
        }
    }
    public function check_logged_in()
    {
        if ($this->session->token) {
            return $this->UserModel->login_token($this->session->token);
        }
        $cred = $this->input->post("credential");
        $g_id_token = $cred ? $cred : $this->session->google_token;
        if (!$g_id_token) {
            return;
        }
        $g_client = $this->UserModel->check_google_logged_in($g_id_token);
        if ($g_client) {
            $this->session->google_token = $g_id_token;
            return $g_client;
        }
    }

    // Seccion licencias

    public function mostrarTS()
    {
        $this->load->model("Trabajadores_model");
        $trabajadores = $this->Trabajadores_model->obtenerTrabajadoresSociales();

        return $trabajadores;
    }
    public function guardar()
    {
        $trabajador_id = $this->input->post("trabajador_id");
        $fecha_inicio = $this->input->post("fecha_inicio");
        $fecha_termino = $this->input->post("fecha_termino");

        $data["licencia_id"] = $this->Trabajadores_model->guardarLicencia(
            $trabajador_id,
            $fecha_inicio,
            $fecha_termino
        );

        if ($data) {
            echo "Licencia registrada con éxito.";
        } else {
            echo "Error al registrar la licencia.";
        }
        $this->Licencia();
    }
    public function Licencia()
    {
        $data["trabajadores"] = $this->mostrarTS();
        $this->load->view("LicenciaView", $data);
    }
}
