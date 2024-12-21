<?php
defined("BASEPATH") or exit("No direct script access allowed");
class CitasController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('TrabajadorSocialModel');
    }

    public function obtenercita()
    {
        // Verificar sesión e identificar tipo de usuario
        $RUN_usuario = $this->check_logged_in();
        $data = $this->comprobardatos($RUN_usuario);

        if ($data['tipo'] === 'estudiante') {
            // Obtener el RUN del trabajador social asignado al estudiante
            $RUNTS = $this->TrabajadorSocialModel->obtenerRUNTS($RUN_usuario);
            $data['citas'] = $this->TrabajadorSocialModel->obtenerCitaEstudiante($RUNTS, $RUN_usuario);
        } elseif ($data['tipo'] === 'noestudiante') {
            // Obtener citas para un usuario no estudiante
            $data['citas'] = $this->TrabajadorSocialModel->obtenerCitasNoEstudiante($RUN_usuario);
        } elseif ($data['tipo'] === 'trabajadorsocial') {
            // Obtener citas asignadas al trabajador social
            $data['citas'] = $this->TrabajadorSocialModel->obtenerCitasPorTS($RUN_usuario);
        } elseif ($data['tipo'] === 'administrador') {
            // Obtener todas las citas (administrador)
            $data['citas'] = $this->TrabajadorSocialModel->obtenerCitasAdministrador();
        } else {
            $data['citas'] = [];
        }

        // Cargar la vista con las citas
        $this->load->view('VisualizarCitas', $data);
    }

    private function check_logged_in()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('login'); // Redirige al login si no está autenticado
        }
        return $this->session->userdata('RUN'); // Devuelve el RUN del usuario
    }

    private function comprobardatos($RUN_usuario)
    {
        // Determinar tipo de usuario en base a la sesión o base de datos
        $tipo_usuario = $this->session->userdata('tipo_usuario'); // Ejemplo: 'estudiante', 'trabajadorsocial', etc.
        return ['tipo' => $tipo_usuario, 'RUN' => $RUN_usuario];
    }
}
