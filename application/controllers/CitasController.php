<?php
defined("BASEPATH") or exit("No direct script access allowed");
class CitasController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('TrabajadorSocialModel');
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
        if ($this->UserModel->getNoEstudiante($RUN_usuario)) {
            $data['tipo'] = 'noestudiante'; // Manejo de caso por defecto
            $data['detalle'] = $this->UserModel->getNoEstudiante( $RUN_usuario );
        }
        return $data;
    }
    public function accion_agendar()
    {
        $this->session->agendar_exito = "";
        $this->session->agendar_error = "";
        $usuario = $this->check_logged_in();
        if (!$usuario) {
            session_destroy();
            redirect("/usuarios/login");
        }
        $fecha_ini = $this->input->post("fecha_ini");
        $fecha_ter = $this->input->post("fecha_ter");
        $motivo = $this->input->post("motivo");
        echo "$fecha_ini, $fecha_ter, $motivo";
        if (!$motivo) {
            $this->session->agendar_error = "Por favor seleccione un Motivo.";
            $this->session->mark_as_flash("agendar_error");
            redirect("/usuarios/agendar");
        }
        if (!$fecha_ini) {
            $this->session->agendar_error = "No hubo una fecha seleccionada.";
            $this->session->mark_as_flash("agendar_error");
            redirect("/usuarios/agendar");
        }
        try {
            $this->BloqueModel->agendar_estudiante(
                $usuario,
                $fecha_ini,
                $fecha_ter,
                $motivo
            );
        } catch (Exception $e) {
            $this->session->agendar_error = $e->getMessage();
            $this->session->mark_as_flash("agendar_error");
            redirect("/usuarios/agendar");
        }
        $this->session->agendar_exito = "Hora agendada con éxito.";
        $this->session->mark_as_flash("agendar_exito");
        redirect("/usuarios/agendar");
    }
    public function get_bloques_no_disponibles_carrera($carrera)
    {
        return $this->BloquesReservadosModel->get_bloques_no_disponibles_carrera(
            $carrera
        );
    }
}
