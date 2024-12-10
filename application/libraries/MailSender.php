<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'application/third_party/phpmailer/src/Exception.php';
require 'application/third_party/phpmailer/src/PHPMailer.php';
require 'application/third_party/phpmailer/src/SMTP.php';

class MailSender {
    private $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);

        // Configuración del servidor SMTP
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.example.com'; // Cambiar por tu servidor SMTP
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'correo-generico@example.com'; // Correo genérico proporcionado
        $this->mail->Password = 'contraseña-segura'; // Contraseña del correo
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port = 587; // Cambiar si tu proveedor usa otro puerto
    }

    public function sendMail($to, $subject, $body) {
        try {
            $this->mail->setFrom('correo-generico@example.com', 'Sistema de Agenda'); // Correo y nombre del remitente
            $this->mail->addAddress($to); // Correo del destinatario

            // Configuración del correo
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            log_message('error', 'Error al enviar correo: ' . $this->mail->ErrorInfo);
            return false;
        }
    }
}
