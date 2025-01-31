<?php
defined("BASEPATH") or exit("No direct script access allowed");

class EliminarCitaController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('EliminarCitaModel');
    }

    // Método para cancelar una cita
    public function eliminarCita()
    {
        // Debug: Inicio de la función
        log_message('debug', '=== INICIO eliminarCita() ===');
        
        // Debug: Datos recibidos del POST
        log_message('debug', 'POST data recibida: ' . json_encode($_POST));
        
        // Obtener los datos del POST
        $idCita = $this->input->post('idCita');
        $runCliente = $this->input->post('runCliente');

        // Debug: Valores extraídos
        log_message('debug', 'ID Cita: ' . $idCita);
        log_message('debug', 'RUN Cliente: ' . $runCliente);

        // Validar que los datos necesarios estén presentes
        if (empty($idCita) || empty($runCliente)) {
            log_message('error', 'ERROR: Datos incompletos - ID: ' . $idCita . ', RUN: ' . $runCliente);
            $this->session->set_flashdata('error', 'Datos incompletos para cancelar la cita.');
            redirect('usuarios/visualizar-citas');
            return;
        }

        // Debug: Antes de llamar al modelo
        log_message('debug', 'Llamando al modelo con ID: ' . $idCita . ' y RUN: ' . $runCliente);
        
        // Intentar cancelar la cita
        $resultado = $this->EliminarCitaModel->cambiarEstadoCita($idCita, $runCliente);

        // Debug: Resultado del modelo
        log_message('debug', 'Resultado del modelo: ' . ($resultado ? 'true' : 'false'));

        if ($resultado) {
            log_message('debug', 'Operación exitosa - Estableciendo mensaje de éxito');
            $this->session->set_flashdata('success', 'Cita cancelada correctamente.');
        } else {
            log_message('error', 'Operación fallida - Estableciendo mensaje de error');
            $this->session->set_flashdata('error', 'No se pudo cancelar la cita. Por favor, intente nuevamente.');
        }

        // Debug: Fin de la función
        log_message('debug', '=== FIN eliminarCita() ===');

        // Redirigir de vuelta a la página de citas
        redirect('usuarios/visualizar-citas');
    }

    public function atenderCita()
    {
        // Debug: Inicio de la función
        log_message('debug', '=== INICIO atenderCita() ===');
        
        $idCita = $this->input->post('idCita');
        $runCliente = $this->input->post('runCliente');

        // Debug: Valores recibidos
        log_message('debug', 'ID Cita: ' . $idCita);
        log_message('debug', 'RUN Cliente: ' . $runCliente);

        if (empty($idCita) || empty($runCliente)) {
            log_message('error', 'Datos incompletos para atender cita');
            $this->session->set_flashdata('error', 'Datos incompletos para marcar la cita como atendida.');
            redirect('usuarios/visualizar-citas');
            return;
        }

        $resultado = $this->EliminarCitaModel->marcarComoAtendido($idCita, $runCliente);

        if ($resultado) {
            $this->session->set_flashdata('success', 'Cita marcada como atendida correctamente.');
        } else {
            $this->session->set_flashdata('error', 'No se pudo marcar la cita como atendida. Por favor, intente nuevamente.');
        }

        redirect('usuarios/visualizar-citas');
    }
} 