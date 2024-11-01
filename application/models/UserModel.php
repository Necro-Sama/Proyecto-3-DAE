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
            INSERT INTO Token VALUES
            (NULL, NULL, ?, ?)
        ", array($usuario->ID_PERSONA, $new_token));
        if (!$insert_new_token_result) {
            $this->session->error = "Error interno en el servidor a la hora de generar el token de acceso.";
            return;
        }
        return $new_token;
    }
    public function login_token($token) {
        $query = $this->db->query("
            SELECT * FROM token_usuarios
            WHERE token_id = ?
            AND NOW() > token_create
            AND NOW() < DATE_ADD(token_create, INTERVAL 10 MINUTE)
        ", array($token));
        if ($query->num_rows() == 1) {
            $query1 = $this->db->query("
                SELECT * FROM usuarios
                WHERE id = ?
            ", array($query->row(0)->user_id));
            return $query1->row(0);
        } elseif ($query->num_rows() > 1) {
            $this->session->error = "Usuario ya logeado.";
        } else {
            $this->session->error = "Token expirado.";
        }
    }
    public function logout($token) {
        $id_usuario = $this->db->query("
            DELETE FROM token_usuarios
            WHERE user_id = (SELECT user_id FROM (SELECT t1.user_id, t1.token_id FROM token_usuarios t1
                             WHERE t1.token_id = ?) t2)
        ", array($token));
        session_destroy();
    }
}
