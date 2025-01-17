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
        $data = $this->comprobardatos($RUN_usuario);
        $this->load->view('navbar', $data);
        $this->load->view('HomeGlobal', $data);
    }
    public function comprobardatos($RUN_usuario) {
        $data['persona'] = $this->UserModel->getPersona($RUN_usuario);
        
        if ($this->UserModel->getEstudiante($RUN_usuario)) {
            $data['tipo'] = 'estudiante';
            $data['detalle'] = $this->UserModel->getEstudiante($RUN_usuario);
        } 
        if ($this->UserModel->getFuncionario($RUN_usuario))  {
            $data['tipo'] = 'trabajadorsocial';
            $data['detalle'] = $this->UserModel->getFuncionario($RUN_usuario);
        } 
        if ($this->UserModel->getAdministrador($RUN_usuario)) {
            $data['tipo'] = 'administrador';
            $data['detalle'] = $this->UserModel->getAdministrador($RUN_usuario);
        } 
        else {
            $data['tipo'] = 'noestudiante'; // Manejo de caso por defecto
            $data['detalle'] = $this->UserModel->getNoEstudiante( $RUN_usuario );
        }
        return $data;
    }
    public function agendar()
    {
        $RUN_usuario = $this->check_logged_in();
        $data = $this->comprobardatos($RUN_usuario);
        if (!$RUN_usuario) {
            session_destroy();
            redirect("/usuarios/login");
        }
        $this->load->view("StudentAgendarView", $data);
    }
    public function gestion_ts($RUN_usuario)
    {
        return $this->UserModel->get_admin($RUN_usuario);
    }
    public function accion_agendar()
    {
        $this->session->agendar_exito = "";
        $this->session->agendar_error = "";
        $usuario = $this->check_logged_in();
        if (!$usuario) {
            session_destroy();
            redirect("/usuarios/login");
        }
        $fecha_ini = $this->input->post("fecha_ini");
        $fecha_ter = $this->input->post("fecha_ter");
        $motivo = $this->input->post("motivo");
        echo "$fecha_ini, $fecha_ter, $motivo";
        if (!$motivo) {
            $this->session->agendar_error = "Por favor seleccione un Motivo.";
            $this->session->mark_as_flash("agendar_error");
            redirect("/usuarios/agendar");
        }
        if (!$fecha_ini) {
            $this->session->agendar_error = "No hubo una fecha seleccionada.";
            $this->session->mark_as_flash("agendar_error");
            redirect("/usuarios/agendar");
        }
        try {
            $this->BloqueModel->agendar_estudiante(
                $usuario,
                $fecha_ini,
                $fecha_ter,
                $motivo
            );
        } catch (Exception $e) {
            $this->session->agendar_error = $e->getMessage();
            $this->session->mark_as_flash("agendar_error");
            redirect("/usuarios/agendar");
        }
        $this->session->agendar_exito = "Hora agendada con éxito.";
        $this->session->mark_as_flash("agendar_exito");
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
    
    public function registrar()
{
    $run = $this->input->post('run', true);

    // Validación de los campos del formulario
    $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim');
    $this->form_validation->set_rules('apellidos', 'Apellido', 'required|trim');
    $this->form_validation->set_rules('telefono', 'Teléfono', 'required|numeric|trim');
    $this->form_validation->set_rules('run', 'RUN', 'required|trim|is_unique[persona.run]', [
        'is_unique' => 'El RUN ya está registrado.'
    ]);
    $this->form_validation->set_rules('correo', 'Correo', 'required|valid_email|is_unique[persona.correo]', [
        'is_unique' => 'El correo ya está registrado.'
    ]);
    $this->form_validation->set_rules('contraseña', 'Contraseña', 'required|min_length[6]|trim');

    if ($this->form_validation->run() == false) {
        // Si la validación falla, redirige al formulario de registro con errores
        $this->session->set_flashdata('error', validation_errors());
        redirect(site_url('usuarios/login'));
    } else {
        // Datos a insertar en la tabla `persona`
        $data = [
            'Nombre'     => $this->input->post('nombre', true),
            'Apellido'   => $this->input->post('apellidos', true),
            'Telefono'   => $this->input->post('telefono', true),
            'RUN'        => $run, // Guardamos el RUN
            'Correo'     => $this->input->post('correo', true),
            'Contraseña' => password_hash($this->input->post('contraseña', true), PASSWORD_DEFAULT), // Encriptar la contraseña
        ];
        $data['Activo'] = 1;

        // Insertar en la tabla `persona`
        if ($this->UserModel->insertUser($data)) {
            // Inserción exitosa en `persona`, continuar con las demás tablas
            $success = true;

            // Insertar en la tabla `cliente`
            $clienteData = ['RUN' => $run];
            if (!$this->UserModel->insertCliente($clienteData)) {
                $success = false;
            }

            // Insertar en la tabla `noestudiante`
            $noEstudianteData = ['RUN' => $run];
            if (!$this->UserModel->insertNoEstudiante($noEstudianteData)) {
                $success = false;
            }

            if ($success) {
                $this->session->set_flashdata('success', 'Usuario registrado con éxito.');
                redirect(site_url('usuarios/login'));
            } else {
                $this->session->set_flashdata('error', 'Error al registrar en las tablas relacionadas. Inténtalo de nuevo.');
                redirect(site_url('usuarios/login'));
            }
        } else {
            $this->session->set_flashdata('error', 'Error al registrar el usuario. Inténtalo de nuevo.');
            redirect(site_url('usuarios/login'));
        }
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

        $resultado = $this->Trabajadores_model->guardarLicencia($trabajador_id, $fecha_inicio, $fecha_termino);

        if ($resultado) {
            // Mensaje de éxito
            $this->session->set_flashdata('mensaje', 'Licencia registrada con éxito.');
            $this->session->set_flashdata('mensaje_tipo', 'success');
        } else {
            // Mensaje de error
            $this->session->set_flashdata('mensaje', 'Error al registrar la licencia.');
            $this->session->set_flashdata('mensaje_tipo', 'danger');
        }

        // Redirige al formulario
        redirect('usuarios/Licencia');
    }
    public function Licencia()
    {
        $RUN_usuario = $this->check_logged_in();
        if (!$this->check_logged_in()) {
            redirect("/usuarios/login");
        }

        $data["trabajadores"] = $this->mostrarTS();
        $data["tipo"] = $this->comprobardatos($RUN_usuario);
        $this->load->view("LicenciaView", $data);
    }
}
