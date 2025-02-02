<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// Si usaste Composer
// require_once FCPATH . 'vendor/autoload.php';

// Si lo descargaste manualmente
require_once APPPATH . 'third_party/tcpdf/tcpdf.php';

class Pdf extends TCPDF {
    public function __construct() {
        parent::__construct();
    }

    // Sobreescribir el header por defecto
    public function Header() {
        // Logo
        // $image_file = K_PATH_IMAGES.'logo.jpg';
        // $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        
        // Título
        $this->SetFont('helvetica', 'B', 16);
        $this->Cell(0, 15, 'Reporte de Estadísticas', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    // Sobreescribir el footer por defecto
    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Página '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
} 