<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CitasController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('CitasModel');
        $this->load->helper('url');
    }

    public function citas_estudiante($id_estudiante) {
        $data['citas'] = $this->CitasModel->obtener_citas_estudiante($id_estudiante);
        $this->load->view('citas_estudiante', $data);
    }

    public function detalle_cita($id_cita) {
        $data['cita'] = $this->CitasModel->obtener_detalle_cita($id_cita);
        $this->load->view('detalle_cita', $data);
    }
}
