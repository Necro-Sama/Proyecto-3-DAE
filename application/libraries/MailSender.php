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
        $this->mail->Host = 'smtp.gmail.com'; // Cambia a tu servidor SMTP
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'gustavo.rios.alvarez@alumnos.uta.cl'; // Tu correo genérico
        $this->mail->Password = ' '; // Contraseña o contraseña de aplicación
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // TLS o SSL según tu servidor
        $this->mail->Port = 587; // Puerto 587 para TLS o 465 para SSL
    }

    public function sendMail($to, $subject, $body) {
        try {
            // Configuración del remitente
            $this->mail->setFrom('correo-generico@dominio.com', 'Sistema de Agenda'); // Cambia a tu correo y nombre genérico
            $this->mail->addAddress($to); // Correo del destinatario

            // Configuración del contenido del correo
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;

            // Enviar correo
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            log_message('error', 'Error al enviar correo: ' . $this->mail->ErrorInfo);
            return false;
        }
    }
}
