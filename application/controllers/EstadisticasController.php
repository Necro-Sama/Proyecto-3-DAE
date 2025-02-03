<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controlador para manejo de estadisticas
 */
class EstadisticasController extends CI_Controller {
    
    /**
     * Constructor - carga modelos necesarios
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('EstadisticasModel');
        $this->load->model('UserModel');
        $this->load->helper('url');
    }

    /**
     * Muestra la vista de estadisticas
     */
    public function EstadisticaView() {
        $RUN_usuario = $this->check_logged_in();
        if (!$RUN_usuario) {
            redirect("/usuarios/login");
        }

        $data = $this->comprobardatos($RUN_usuario);
        if ($data['tipo'] !== 'administrador') {
            redirect('usuarios/home');
            return;
        }

        $this->load->view('EstadisticaView', $data);
    }

    
    /**
     * Obtiene datos para los graficos via AJAX
     */
    public function obtenerDatos() {
        header('Content-Type: application/json');
        ob_clean();
        
        try {
            $estadisticas = $this->EstadisticasModel->obtenerEstadisticas();
            echo json_encode(['data' => $estadisticas]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit();
    }

    /**
     * Genera PDF con estadisticas
     */
    public function exportarPDF() {
        try {
            // Cargar librería y datos
            $this->load->library('pdf');
            $estadisticas = $this->EstadisticasModel->obtenerEstadisticas();
            
            // Inicializar PDF
            $pdf = new PDF();
            $pdf->SetCreator('Sistema DAE');
            $pdf->SetAuthor('Administrador');
            $pdf->SetTitle('Reporte de Estadísticas');
            
            // Agregar página
            $pdf->AddPage();
            
            // Configurar fuente
            $pdf->SetFont('helvetica', 'B', 14);
            
            // Estadísticas por Carrera
            $pdf->Cell(0, 10, 'Estadísticas por Carrera', 0, 1, 'L');
            $pdf->SetFont('helvetica', '', 12);
            foreach ($estadisticas['por_carrera'] as $item) {
                $pdf->Cell(100, 8, $item['nombre'], 0, 0, 'L');
                $pdf->Cell(30, 8, $item['total'], 0, 1, 'R');
            }
            
            $pdf->Ln(10);
            
            // Estadísticas por Motivo
            $pdf->SetFont('helvetica', 'B', 14);
            $pdf->Cell(0, 10, 'Estadísticas por Motivo', 0, 1, 'L');
            $pdf->SetFont('helvetica', '', 12);
            foreach ($estadisticas['por_motivo'] as $item) {
                $pdf->Cell(100, 8, $item['nombre'], 0, 0, 'L');
                $pdf->Cell(30, 8, $item['total'], 0, 1, 'R');
            }
            
            $pdf->Ln(10);
            
            // Estadísticas por Estado
            $pdf->SetFont('helvetica', 'B', 14);
            $pdf->Cell(0, 10, 'Estadísticas por Estado', 0, 1, 'L');
            $pdf->SetFont('helvetica', '', 12);
            foreach ($estadisticas['por_estado'] as $item) {
                $pdf->Cell(100, 8, $item['nombre'], 0, 0, 'L');
                $pdf->Cell(30, 8, $item['total'], 0, 1, 'R');
            }
            
            // Generar PDF
            $pdf->Output('estadisticas.pdf', 'D');
            
        } catch (Exception $e) {
            log_message('error', 'Error al generar PDF: ' . $e->getMessage());
            echo "Error al generar el PDF: " . $e->getMessage();
        }
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
}
