<?php
defined("BASEPATH") or exit("No direct script access allowed");

class LicenciaController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library("session");
        $this->load->model("UserModel");
        $this->load->model("BloqueModel");
        $this->load->model("Trabajadores_model");
        $this->load->helper("url");
        $this->load->helper("form");
        $this->load->library("form_validation");

        $this->form_validation->set_rules(
            "correo",
            "Correo",
            "required|valid_email"
        );
        $this->form_validation->set_rules(
            "contraseña",
            "Contraseña",
            "required"
        );
        $this->form_validation->set_error_delimiters(
            '<div class="error">',
            "</div>"
        );
    }
    public function mostrarTS()
        {
            $this->load->model("Trabajadores_model");
            $trabajadores = $this->Trabajadores_model->obtenerTrabajadoresSociales();

            return $trabajadores;
        }
    public function guardar()
    {
        $trabajador_id = $this->input->post("trabajador_id");
        $fecha_inicio = $this->input->post("fecha_inicio");
        $fecha_termino = $this->input->post("fecha_termino");

        $resultado = $this->Trabajadores_model->guardarLicencia($trabajador_id, $fecha_inicio, $fecha_termino);

        if ($resultado) {
            // Mensaje de éxito
            $this->session->set_flashdata('mensaje', 'Licencia registrada con éxito.');
            $this->session->set_flashdata('mensaje_tipo', 'success');
        } else {
            // Mensaje de error
            $this->session->set_flashdata('mensaje', 'Error al registrar la licencia.');
            $this->session->set_flashdata('mensaje_tipo', 'danger');
        }

        // Redirige al formulario
        redirect('usuarios/Licencia');
    }
    public function Licencia()
    {
        $RUN_usuario = $this->check_logged_in();
        if (!$this->check_logged_in()) {
            redirect("/usuarios/login");
        }

        $data["trabajadores"] = $this->mostrarTS();
        $data["tipo"] = $this->comprobardatos($RUN_usuario);
        $this->load->view("LicenciaView", $data);
    }
}