<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EstadisticasController extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('EstadisticasModel');
    }

    public function index() {
        if (!$this->session->userdata('logged_in')) {
            redirect('usuarios/login');
        }
        
        $data['tipo'] = $this->session->userdata('tipo');
        if ($data['tipo'] !== 'administrador') {
            redirect('usuarios/home');
        }
        
        $año_actual = date('Y');
        $data['estadisticas'] = $this->EstadisticasModel->obtenerEstadisticas($año_actual);
        $this->load->view('EstadisticaView', $data);
    }

    public function obtenerDatos() {
        $fecha_inicio = $this->input->post('fecha_inicio');
        $fecha_fin = $this->input->post('fecha_fin');
        $estadisticas = $this->EstadisticasModel->obtenerEstadisticas($fecha_inicio, $fecha_fin);
        echo json_encode($estadisticas);
    }
}
