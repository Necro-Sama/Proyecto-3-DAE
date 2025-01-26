<?php
defined("BASEPATH") or exit("No direct script access allowed");
class CitasController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('TrabajadorSocialModel');
        $this->load->model('CitasModel');
        $this->load->model('UserModel');
    }
    // Método para eliminar una cita
    public function eliminarCita()
    {
        // Obtener los datos desde el POST
        $idCita = $this->input->post('idCita');
        $runCliente = $this->input->post('runCliente');

        // Llamar al modelo para eliminar la cita
        $eliminado = $this->CitasModel->eliminarCita($idCita, $runCliente);

        if ($eliminado) {
            $this->session->set_flashdata('success', 'Cita cancelada correctamente.');
        } else {
            $this->session->set_flashdata('error', 'Hubo un error al intentar cancelar la cita.');
        }

        // Redirigir a la misma página
        redirect('usuarios/visualizar-citas');
    }
    function estadobloque($id_bloque){
        return $this->CitasModel->verificar_bloque($id_bloque);
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
        else if ($this->UserModel->getNoEstudiante($RUN_usuario)) {
            $data['tipo'] = 'noestudiante'; 
            $data['detalle'] = $this->UserModel->getNoEstudiante( $RUN_usuario );
        }
        else{
            $data['tipo']='';
        }
        return $data;
    }
    public function bloquear()
    {
        log_message('debug', '=== Iniciando función bloquear ===');
        log_message('debug', 'POST recibido: ' . print_r($_POST, true));

        // Obtener datos del POST
        $id = $this->input->post('ID');
        $fechainicio = $this->input->post('fechainicio');
        $fechafinal = $this->input->post('fechafinal');
        $run = $this->input->post('RUN');

        // Verificar que tenemos todos los datos necesarios
        if (empty($id) || empty($fechainicio) || empty($fechafinal) || empty($run)) {
            log_message('error', 'Faltan datos requeridos');
            $this->session->set_flashdata('error', 'Faltan datos requeridos');
            redirect($_SERVER['HTTP_REFERER']);
            return;
        }

        // Preparar datos para insertar
        $data = array(
            'ID' => $id,
            'fechainicio' => $fechainicio,
            'fechafinal' => $fechafinal,
            'RUN' => $run
        );

        log_message('debug', 'Intentando insertar con datos: ' . print_r($data, true));

        // Cargar el modelo e intentar la inserción
        $this->load->model('CitasModel');
        $result = $this->CitasModel->insertarBloqueBloqueado($data);

        if ($result) {
            log_message('debug', 'Inserción exitosa');
            $this->session->set_flashdata('success', 'Bloque bloqueado exitosamente');
        } else {
            log_message('error', 'Error al insertar en la base de datos');
            $this->session->set_flashdata('error', 'No se pudo bloquear el horario');
        }

        // Mostrar mensaje flash
        $this->session->set_flashdata('debug_info', 'Datos procesados: ' . print_r($data, true));

        redirect($_SERVER['HTTP_REFERER']);
    }
    //seccion reagendar
    public function abrirreagendar()
    {
        // Obtén el ID de la cita desde el formulario
        $idCita = $this->input->post('idCita');
        
        // Verifica si el usuario está autenticado
        $run = $this->check_logged_in();
        
        // Obtén los datos necesarios
        $data = $this->comprobardatos($run);
        $data['reagenda'] = true;
        $data['eliminar'] = $idCita;
        
        // Carga la vista de reagendar
        return $this->load->view('ReagendarView', $data);
    }
    public function reagendar(){
        //llamara a eliminar cita para borrar la anterior y pasara a tomar la nueva una vez terminado enviara un mensaje de reagendado con exito.

    }
}