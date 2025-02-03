<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TrabajadorSocialController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('UserModel');
        $this->load->model('TrabajadorSocialModel');
        $this->load->model('CarreraModel');
        $this->load->helper('url');
        $this->load->model('EliminarCitaModel');
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
    public function obtenerCarreras() {
        $this->db->select('
            c.COD_CARRERA,
            SUBSTRING_INDEX(c.Nombre, " - ", 1) as Nombre,
            c.RUNTS,
            c.ReemplazaRUNTS,
            p1.Activo as Activo_Principal,
            p2.Activo as Activo_Reemplazo
        ');
        $this->db->from('carrera c');
        $this->db->join('trabajadorsocial ts1', 'c.RUNTS = ts1.RUN', 'left');
        $this->db->join('persona p1', 'ts1.RUN = p1.RUN', 'left');
        $this->db->join('trabajadorsocial ts2', 'c.ReemplazaRUNTS = ts2.RUN', 'left');
        $this->db->join('persona p2', 'ts2.RUN = p2.RUN', 'left');
        $this->db->order_by('c.Nombre', 'ASC');
        
        return $this->db->get()->result_array();
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
        
        $this->TrabajadorSocialModel->actualizar_estados_automaticamente();
    
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
        $citas = $this->Citas_model->get_citas_futuras($futuras);
        echo json_encode($citas);
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
    public function marcarComoAtendida() {
        // Asegurarnos de que la respuesta sea JSON
        header('Content-Type: application/json');
        
        try {
            // Verificar si hay datos POST
            $input = file_get_contents('php://input');
            if (empty($input)) {
                throw new Exception('No se recibieron datos');
            }

            $data = json_decode($input, true);
            if (!isset($data['idCita'])) {
                throw new Exception('ID de cita no proporcionado');
            }

            $idCita = $data['idCita'];
            
            // Verificar que el usuario sea trabajador social
            $RUN_usuario = $this->check_logged_in();
            if (!$RUN_usuario) {
                throw new Exception('Usuario no autenticado');
            }

            $userData = $this->comprobardatos($RUN_usuario);
            if ($userData['tipo'] !== 'trabajadorsocial') {
                throw new Exception('Usuario no autorizado');
            }
            
            // Verificar que la cita exista y esté en estado Reservado
            $this->db->where('ID', $idCita);
            $this->db->where('Estado', 'Reservado');
            $cita = $this->db->get('bloqueatencion')->row();
            
            if (!$cita) {
                throw new Exception('La cita no existe o no está en estado Reservado');
            }

            // Actualizar el estado de la cita
            $this->db->where('ID', $idCita);
            $result = $this->db->update('bloqueatencion', ['Estado' => 'Atendido']);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Cita actualizada correctamente']);
            } else {
                throw new Exception('Error al actualizar la cita en la base de datos');
            }

        } catch (Exception $e) {
            log_message('error', 'Error en marcarComoAtendida: ' . $e->getMessage());
            echo json_encode([
                'success' => false, 
                'message' => $e->getMessage(),
                'debug' => ENVIRONMENT === 'development' ? $e->getTraceAsString() : null
            ]);
        }
    }
    public function visualizar_citas() {
        try {
            // ... código existente ...
            
            $this->load->model('CitasModel');
            $citas = $this->CitasModel->obtenerCitasUsuario($run);
            
            // Debug para verificar los datos
            log_message('debug', 'Datos enviados a la vista: ' . json_encode($citas));
            
            $data['citas'] = $citas;
            $this->load->view('VisualizarCitas', $data);
            
        } catch (Exception $e) {
            // ... manejo de errores ...
        }
    }
}
