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
    public function bloquear() {
        // Definir los campos requeridos
        $required_fields = ['ID', 'RUN', 'fechainicio', 'fechafinal'];
        $data = [];
        
        // Log para debug
        log_message('debug', 'Datos POST recibidos en bloquear(): ' . print_r($this->input->post(), TRUE));
        
        // Validar cada campo requerido
        foreach ($required_fields as $field) {
            if (!$this->input->post($field)) {
                $this->session->set_flashdata('error', 'Falta el campo requerido: ' . $field);
                log_message('error', 'Campo faltante en bloquear(): ' . $field);
                redirect('usuarios/calendario');
                return;
            }
            $data[$field] = $this->input->post($field);
        }

        // Validar formato de fechas
        if (!strtotime($data['fechainicio']) || !strtotime($data['fechafinal'])) {
            $this->session->set_flashdata('error', 'Formato de fecha inválido');
            log_message('error', 'Formato de fecha inválido en bloquear()');
            redirect('usuarios/calendario');
            return;
        }

        // Intentar insertar el bloque bloqueado
        $result = $this->CitasModel->insertarBloqueBloqueado($data);

        if ($result === 'bloqueado') {
            $this->session->set_flashdata('error', 'Este horario ya está bloqueado');
            log_message('info', 'Intento de bloquear un horario ya bloqueado');
        } elseif ($result === true) {
            $this->session->set_flashdata('success', 'Bloque bloqueado exitosamente');
            log_message('debug', 'Bloque bloqueado exitosamente');
        } else {
            $this->session->set_flashdata('error', 'Error al bloquear el bloque');
            log_message('error', 'Error al bloquear el bloque en bloquear()');
        }

        redirect('usuarios/agendar');
    }
    //seccion reagendar
    public function obtener_horarios() {
        $start = $this->input->get('start');
        $end = $this->input->get('end');
        
        $horarios = $this->CitasModel->obtenerHorariosEnRango($start, $end);
        
        header('Content-Type: application/json');
        echo json_encode($horarios);
    }
}