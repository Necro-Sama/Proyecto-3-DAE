<?php
defined('BASEPATH') or exit('No direct script access allowed');

$config['protocol'] = 'smtp';
$config['smtp_host'] = 'smtp.gmail.com'; // Servidor SMTP
$config['smtp_user'] = 'gustavo.rios.alvarez@alumnos.uta.cl'; // Tu correo
$config['smtp_pass'] = 'ojei tdhz jtsn kaju'; // Tu contraseña
$config['smtp_port'] = 587; // Puerto SMTP
$config['smtp_crypto'] = 'tls'; // Seguridad
$config['mailtype'] = 'html'; // Tipo de correo
$config['charset'] = 'utf-8';
$config['wordwrap'] = TRUE;
$config['newline'] = "\r\n";
