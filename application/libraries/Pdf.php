<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// Corregir la ruta al archivo TCPDF
require_once APPPATH . 'third_party/TCPDF-main/tcpdf.php';

/**
 * Libreria para generar PDFs
 * Extiende TCPDF
 */
class Pdf extends TCPDF {
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Encabezado del PDF
     */
    public function Header() {
        $this->SetFont('helvetica', 'B', 16);
        $this->Cell(0, 15, 'Reporte de Estadísticas', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    /**
     * Pie de pagina del PDF
     */
    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Página '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
} 