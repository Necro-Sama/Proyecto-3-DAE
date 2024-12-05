<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TrabajadorSocialController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('UserModel');
        $this->load->model('TrabajadorSocialModel');
        $this->load->model('CarreraModel');
        $this->load->helper('url');
    }

    // Método para mostrar el formulario de asignación
    public function asignarTSACarrera() {
        // Obtener todas las carreras
        $data['carreras'] = $this->CarreraModel->obtenerCarreras();
        
        // Obtener todos los trabajadores sociales
        $data['trabajadores_sociales'] = $this->TrabajadorSocialModel->obtenerTrabajadoresSociales();

        // Cargar la vista
        $this->load->view('AsignarCarrerasView', $data);
    }

    // Método para procesar la asignación de trabajadores sociales
    public function asignarTSACarreraProcesar() {
        // Obtener los datos del formulario
        $cod_carrera = $this->input->post('COD_CARRERA');
        $run_ts_principal = $this->input->post('RUN_TS_PRINCIPAL');
        $run_ts_reemplazo = $this->input->post('RUN_TS_REEMPLAZO');
        echo "<script>console.log('Entrando al método asignarTSACarreraProcesar con los datos: " . json_encode($this->input->post()) . "');</script>";

        // Aquí puedes procesar la asignación (ej., guardar la relación en la base de datos)
        $this->CarreraModel->asignarTrabajadorSocialACarrera($cod_carrera, $run_ts_principal, $run_ts_reemplazo);

        // Redirigir a otra página o mostrar mensaje de éxito
        redirect('usuarios/asignar-carrera');
    }
    public function index() {
        if (!$this->check_logged_in()) {
            redirect("/usuarios/login");
        }
        $trabajadores['trabajadores'] = $this->TrabajadorSocialModel->obtenerTrabajadoresSociales();
        $trabajadores['tipo']="administrador";
        // print_r($trabajadores);
        $this->load->view('gestor_ts',$trabajadores);
    }

    public function agregar() {
        $datosPersona = [
            'RUN' => $this->input->post('RUN'),
            'Nombre' => $this->input->post('Nombre'),
            'Apellido' => $this->input->post('Apellido'),
            'Correo' => $this->input->post('Correo'),
            'Telefono' => $this->input->post('Telefono'),
            'Contraseña' => password_hash($this->input->post('Contraseña'), PASSWORD_BCRYPT),
        ];

        $this->TrabajadorSocialModel->agregarTrabajadorSocial($datosPersona, []);
        redirect('gestor_ts');
    }

    public function eliminar($RUN) {
        $this->TrabajadorSocialModel->eliminarTrabajadorSocial($RUN);
        redirect('gestor_ts');
    }

    public function editar($RUN) {
        $datosPersona = [
            'Nombre' => $this->input->post('Nombre'),
            'Apellido' => $this->input->post('Apellido'),
            'Correo' => $this->input->post('Correo'),
            'Telefono' => $this->input->post('Telefono')
        ];
    
        $this->TrabajadorSocialModel->actualizarTrabajadorSocial($RUN, $datosPersona);
        $this->index();
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
    public function obtenercita()
{
    $RUN_usuario = $this->check_logged_in();
    $data['tipo'] = $this->comprobardatos($RUN_usuario);

    if ($data['tipo'] === 'estudiante') {
        $RUNTS = $this->TrabajadorSocialModel->obtenerRUNTS($RUN_usuario);
        $data['citas'] = $this->TrabajadorSocialModel->obtenerCitaEstudiante($RUNTS, $RUN_usuario);
    } elseif ($data['tipo'] === 'noestudiante') {
        $data['citas'] = $this->TrabajadorSocialModel->obtenerCitasNoEstudiante($RUN_usuario);
    } elseif ($data['tipo'] === 'trabajadorsocial') {
        $data['citas'] = $this->TrabajadorSocialModel->obtenerCitasPorTS($RUN_usuario);
    } elseif ($data['tipo'] === 'administrador') {
        $data['citas'] = $this->TrabajadorSocialModel->obtenerCitasAdministrador();
    } else {
        $data['citas'] = [];
    }

    $this->load->view('VisualizarCitas', $data);
}


    public function comprobardatos($RUN_usuario) {
        $data['persona'] = $this->UserModel->getPersona($RUN_usuario);
        
        if ($this->UserModel->getEstudiante($RUN_usuario)) {
            $data['tipo'] = 'estudiante';
            $data['detalle'] = $this->UserModel->getEstudiante($RUN_usuario);
        } 
        else if ($this->UserModel->getFuncionario($RUN_usuario)) {
            $data['tipo'] = 'trabajadorsocial';
            $data['detalle'] = $this->UserModel->getFuncionario($RUN_usuario);
        } 
        else if ($this->UserModel->getAdministrador($RUN_usuario)) {
            $data['tipo'] = 'administrador';
            $data['detalle'] = $this->UserModel->getAdministrador($RUN_usuario);
        } 
        else {
            $data['tipo'] = 'noestudiante'; // Manejo de caso por defecto
            $data['detalle'] = $this->UserModel->getNoEstudiante( $RUN_usuario );
        }
        return $data;
    }
    public function agregarAdmin() {
        $data = json_decode($this->input->raw_input_stream, true);
        $run = $data['RUN'];
    
        // Verificar si el TS ya está en la tabla 'administrador'
        $this->db->where('RUN', $run);
        $query = $this->db->get('administrador');
    
        if ($query->num_rows() == 0) {
            // Insertar el RUN del trabajador social en la tabla 'administrador'
            $this->db->insert('administrador', ['RUN' => $run]);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    }
    public function eliminarAdmin() {
        $data = json_decode($this->input->raw_input_stream, true);
        $run = $data['RUN'];
    
        // Eliminar el TS de la tabla 'administrador'
        $this->db->where('RUN', $run);
        $this->db->delete('administrador');
    
        echo json_encode(['success' => true]);
    }
    
    
}
