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
        $RUN_usuario = $this->check_logged_in();
        // Obtener todos los trabajadores sociales
        $data['trabajadores_sociales'] = $this->TrabajadorSocialModel->obtenerTrabajadoresSociales();
        $data['tipo']= $this->comprobardatos($RUN_usuario);
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
        redirect('/usuarios/gestor_ts');
    }
    public function eliminar($RUN) {
        $this->TrabajadorSocialModel->eliminarTrabajadorSocial($RUN);
        redirect('/usuarios/gestor_ts');
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
        if (!$RUN_usuario) {
            session_destroy();
            redirect("/usuarios/login");
        }
        $filtro = $this->input->get('filtro'); // Obtener filtro desde la vista (puede ser RUN o nombre)

        $data = $this->comprobardatos($RUN_usuario);
        if ($data['tipo'] === 'estudiante') {
            $RUNTS = $this->TrabajadorSocialModel->obtenerRUNTS($RUN_usuario);
            $data['citas'] = $this->TrabajadorSocialModel->obtenerCitaEstudiante($RUNTS, $RUN_usuario, $filtro);
        } else if ($data['tipo'] === 'noestudiante') {
            $data['citas'] = $this->TrabajadorSocialModel->obtenerCitasNoEstudiante($RUN_usuario, $filtro);
        } else if ($data['tipo'] === 'trabajadorsocial') {
            $data['citas'] = $this->TrabajadorSocialModel->obtenerCitasPorTS($RUN_usuario, $filtro);
        } else if ($data['tipo'] === 'administrador') {
            $data['citas'] = $this->TrabajadorSocialModel->obtenerCitasAdministrador($filtro);
        } else {
            $data['citas'] = [];
        }
        //print_r($data);
        $this->load->view('VisualizarCitas', $data);
    }
    public function filtrar_citas()
    {
        $futuras = $this->input->post('futuras');
        $citas = $this->Citas_model->get_citas_futuras($futuras); // Asumiendo que tienes un modelo que retorna las citas filtradas

        echo json_encode($citas); // Devolver las citas como JSON
    }
    public function comprobardatos($RUN_usuario) {
        $data['persona'] = $this->UserModel->getPersona($RUN_usuario);
        
        if ($this->UserModel->getEstudiante($RUN_usuario)) {
            $data['tipo'] = 'estudiante';
            $data['detalle'] = $this->UserModel->getEstudiante($RUN_usuario);
        } 
        else if ($this->UserModel->getFuncionario($RUN_usuario))  {
            $data['tipo'] = 'trabajadorsocial';
            $data['detalle'] = $this->UserModel->getFuncionario($RUN_usuario);
        } 
        else if ($this->UserModel->getAdministrador($RUN_usuario)) {
            $data['tipo'] = 'administrador';
            $data['detalle'] = $this->UserModel->getAdministrador($RUN_usuario);
        } 
        else if ($this->UserModel->getNoEstudiante($RUN_usuario)) {
            $data['tipo'] = 'noestudiante';
            $data['detalle'] = $this->UserModel->getNoEstudiante( $RUN_usuario );
        }
        else{
            $data['tipo']='';
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
    public function cambiartipo()
{
    // Leer el cuerpo de la solicitud
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['RUN'])) {
        $run = $data['RUN'];
        
        // Consultar si el trabajador social ya es administrador
        $this->load->model('TrabajadorSocialModel');
        $es_admin = $this->TrabajadorSocialModel->esAdmin($run);

        // Cambiar el estado
        $resultado = false;
        if ($es_admin) {
            // Si es administrador, eliminarlo de la tabla
            $resultado = $this->TrabajadorSocialModel->eliminarAdmin($run);
        } else {
            // Si no es administrador, agregarlo a la tabla
            $resultado = $this->TrabajadorSocialModel->agregarAdmin($run);
        }

        if ($resultado) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el tipo']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'RUN no proporcionado']);
    }
}
    
}
