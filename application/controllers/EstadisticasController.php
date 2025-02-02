<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EstadisticasController extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('EstadisticasModel');
        $this->load->model('UserModel');
        $this->load->helper('url');
    }

    public function cargarVista() {
        $RUN_usuario = $this->check_logged_in();
        if (!$RUN_usuario) {
            redirect("/usuarios/login");
        }

        $data = $this->comprobardatos($RUN_usuario);
        
        if ($data['tipo'] !== 'administrador') {
            redirect('usuarios/home');
            return;
        }

        // Si llegamos aquí, el usuario es administrador
        $año_actual = date('Y');
        $data['estadisticas'] = $this->EstadisticasModel->obtenerEstadisticas($año_actual);
        $this->load->view('EstadisticaView', $data);
    }

    public function check_logged_in() {
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
            $data['detalle'] = $this->UserModel->getNoEstudiante($RUN_usuario);
        }
        else {
            $data['tipo'] = '';
        }
        return $data;
    }

    public function obtenerDatos() {
        if (!$this->check_logged_in()) {
            echo json_encode(['error' => 'No hay sesión activa']);
            return;
        }

        $fecha_inicio = $this->input->post('fecha_inicio');
        $fecha_fin = $this->input->post('fecha_fin');

        // Validar fechas
        if (!$fecha_inicio || !$fecha_fin) {
            $fecha_inicio = date('Y-01-01');
            $fecha_fin = date('Y-12-31');
        }

        $estadisticas = $this->EstadisticasModel->obtenerEstadisticas($fecha_inicio, $fecha_fin);
        echo json_encode($estadisticas);
    }
}
