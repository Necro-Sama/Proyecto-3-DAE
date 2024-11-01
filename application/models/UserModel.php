<?php
class UserModel extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
    }
    public function login($email, $password) {
        $this->session->unset_userdata("error");
        $this->session->unset_userdata("form_error");
        $query = $this->db->query("
            SELECT * FROM Persona
            WHERE CORREO = ?
        ", array($email));
        if ($query->num_rows() == 0) {
            $this->session->error = "Correo o contraseña incorrectos.";
            return;
        }
        if ($query->num_rows() > 1) {
            $this->session->error = "Esta cuenta es inválida.";
            return;
        }
        $usuario = $query->row(0);
        // Check password
        if (!password_verify($password, $usuario->CONTRASEÑA)) {
            $this->session->error = "Correo o contraseña incorrectos.";
            return;
        }
        $new_token = random_bytes(100);
        $insert_new_token_result = $this->db->query("
            INSERT INTO TOKEN VALUES
            (?, NULL, ?)
        ", array($new_token, $usuario->RUN));
        if (!$insert_new_token_result) {
            $this->session->error = "Error interno en el servidor a la hora de generar el token de acceso.";
            return;
        }
        return $new_token;
    }
    public function login_token($token) {
        $query = $this->db->query("
            SELECT * FROM TOKEN
            WHERE TOKEN_ID = ?
            AND NOW() > FECHA_CREACION
            AND NOW() < DATE_ADD(FECHA_CREACION, INTERVAL 10 MINUTE)
        ", array($token));
        if ($query->num_rows() == 1) {
            $query1 = $this->db->query("
                SELECT * FROM Persona
                WHERE RUN = ? 
            ", array($query->row(0)->RUN));
            return $query1->row(0);
        } elseif ($query->num_rows() > 1) {
            $this->session->error = "Usuario ya logeado.";
        } else {
            $this->session->error = "Token expirado.";
        }
    }
    public function logout($token) {
        $id_usuario = $this->db->query("
            DELETE FROM TOKEN
            WHERE RUN = (SELECT RUN FROM (SELECT t1.RUN, t1.TOKEN_ID FROM TOKEN t1
                                          WHERE t1.TOKEN_ID = ?) t2)
        ", array($token));
        session_destroy();
    }
    public function get_trabajador_social($RUN) {
        $query = $this->db->query("
            SELECT * FROM TS
            WHERE RUN = ?
        ", array($RUN));
        if (!$query->num_rows()) {
            return;
        }
        return $query->row(0);
    }
    public function get_estudiante($RUN) {
        $query = $this->db->query("
            SELECT * FROM Estudiante
            WHERE RUN = ?
        ", array($RUN));
        if (!$query->num_rows()) {
            return;
        }
        return $query->row(0);
    }
}
