<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EstadisticasController extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('EstadisticasModel');
        $this->load->model('UserModel');
        $this->load->helper('url');
    }

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

    public function exportarPDF() {
        // Cargar la librería PDF
        $this->load->library('pdf');
        
        // Obtener los datos
        $data['estadisticas'] = $this->EstadisticasModel->obtenerEstadisticas();
        
        // Crear nuevo PDF
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // Configurar el documento
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Sistema de Citas');
        $pdf->SetTitle('Estadísticas de Citas');
        
        // Configurar márgenes
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);
        
        // Agregar página
        $pdf->AddPage();
        
        // Establecer fuente
        $pdf->SetFont('helvetica', '', 12);
        
        // Título
        $pdf->Cell(0, 10, 'Estadísticas de Citas', 0, 1, 'C');
        $pdf->Ln(10);
        
        // Estadísticas por Carrera
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'Por Carrera', 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 12);
        foreach ($data['estadisticas']['por_carrera'] as $item) {
            $pdf->Cell(140, 8, $item['nombre'], 0, 0);
            $pdf->Cell(40, 8, $item['total'] . ' citas', 0, 1, 'R');
        }
        $pdf->Ln(10);
        
        // Estadísticas por Motivo
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'Por Motivo', 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 12);
        foreach ($data['estadisticas']['por_motivo'] as $item) {
            $pdf->Cell(140, 8, $item['nombre'], 0, 0);
            $pdf->Cell(40, 8, $item['total'] . ' citas', 0, 1, 'R');
        }
        $pdf->Ln(10);
        
        // Estadísticas por Estado
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'Por Estado', 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 12);
        foreach ($data['estadisticas']['por_estado'] as $item) {
            $pdf->Cell(140, 8, $item['nombre'], 0, 0);
            $pdf->Cell(40, 8, $item['total'] . ' citas', 0, 1, 'R');
        }
        
        // Generar el PDF
        $pdf->Output('estadisticas_citas.pdf', 'D');
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
