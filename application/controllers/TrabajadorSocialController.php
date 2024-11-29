<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TrabajadorSocialController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('TrabajadorSocialModel');
        $this->load->model('UserModel');
        $this->load->helper('url');
    }

    public function index() {
        if (!$this->check_logged_in()) {
            redirect("/usuarios/login");
        }
        $trabajadores = $this->TrabajadorSocialModel->obtenerTrabajadoresSociales();
        $this->load->view('gestor_ts', array("trabajadores" => $trabajadores));
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
        redirect('gestor_ts');
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
}
?>
