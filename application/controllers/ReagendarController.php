<?php
defined("BASEPATH") or exit("No direct script access allowed");

class ReagendarController extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library("session");
        $this->load->model("UserModel");
        $this->load->model("ReagendarModel");
        $this->load->helper("url");
    }

    public function iniciarReagendamiento() {
        // Verificar sesión
        $run = $this->check_logged_in();
        if (!$run) {
            redirect('usuarios/login');
            return;
        }

        try {
            // Validar y obtener datos de la cita
            $idCita = $this->validarCita();
            $citaOriginal = $this->obtenerYValidarCita($idCita);
            
            // Cargar el modelo de calendario
            $this->load->model('BloqueModel');
            
            // Preparar datos para la vista
            $datosUsuario = $this->comprobardatos($run);
            $data = array_merge($datosUsuario, [
                'run' => $run,
                'reagenda' => true,
                'idCita' => $idCita,
                'citaOriginal' => $citaOriginal,
                'debug' => true
            ]);

            // Debug
            log_message('debug', 'Datos enviados a la vista: ' . json_encode($data));

            $this->load->view("StudentAgendarView", $data);

        } catch (Exception $e) {
            log_message('error', 'Error en reagendamiento: ' . $e->getMessage());
            $this->session->set_flashdata('error', $e->getMessage());
            redirect('usuarios/visualizar-citas');
        }
    }

    private function validarCita() {
        $idCita = $this->input->get('idCita');
        if (!$idCita) {
            throw new Exception('ID de cita no proporcionado');
        }
        return $idCita;
    }

    private function obtenerYValidarCita($idCita) {
        $citaOriginal = $this->ReagendarModel->obtenerCitaPorId($idCita);
        
        if (!$citaOriginal) {
            throw new Exception('Cita no encontrada');
        }

        if ($citaOriginal['Estado'] !== 'Reservado') {
            throw new Exception('Solo se pueden reagendar citas con estado Reservado');
        }

        $this->validarTiempoCita($citaOriginal['FechaInicio']);

        return $citaOriginal;
    }

    private function validarTiempoCita($fechaInicio) {
        $fechaCita = new DateTime($fechaInicio);
        $ahora = new DateTime();
        $diferencia = $fechaCita->diff($ahora);
        $minutosDiferencia = ($diferencia->days * 24 * 60) + ($diferencia->h * 60) + $diferencia->i;

        if ($minutosDiferencia <= 15) {
            throw new Exception('No se puede reagendar una cita menos de 15 minutos antes');
        }
    }

    private function check_logged_in() {
        if ($this->session->token) {
            return $this->UserModel->login_token($this->session->token);
        }
        return false;
    }

    private function comprobardatos($RUN_usuario) {
        $data['persona'] = $this->UserModel->getPersona($RUN_usuario);
        
        if ($this->UserModel->getEstudiante($RUN_usuario)) {
            $data['tipo'] = 'estudiante';
            $data['detalle'] = $this->UserModel->getEstudiante($RUN_usuario);
        } 
        else if ($this->UserModel->getFuncionario($RUN_usuario)) {
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

    public function procesarReagendamiento() {
        $run = $this->check_logged_in();
        if (!$run) {
            redirect('usuarios/login');
            return;
        }

        try {
            $idCitaAnterior = $this->input->post('idCitaAnterior');
            $nuevaFechaInicio = $this->input->post('fecha_inicio');
            $nuevaFechaFin = $this->input->post('fecha_fin');
            $motivo = $this->input->post('motivo');

            // Validar datos
            if (!$idCitaAnterior || !$nuevaFechaInicio || !$nuevaFechaFin || !$motivo) {
                throw new Exception('Datos incompletos para el reagendamiento');
            }

            // Procesar el reagendamiento
            $resultado = $this->ReagendarModel->procesarReagendamiento(
                $idCitaAnterior,
                $nuevaFechaInicio,
                $nuevaFechaFin,
                $motivo,
                $run
            );

            $this->session->set_flashdata('success', 'Cita reagendada exitosamente');
            redirect('usuarios/visualizar-citas');

        } catch (Exception $e) {
            $this->session->set_flashdata('error', $e->getMessage());
            redirect('usuarios/visualizar-citas');
        }
    }
}