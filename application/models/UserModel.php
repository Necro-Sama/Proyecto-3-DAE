<?php
class UserModel extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
    }
    public function login($email, $password, $user_type) {
        $this->db->select("*");
        $this->db->from("usuarios");
        $this->db->where("email", $email);
        $this->db->where("user_type", $user_type);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            $usuario = $query->row(0);
            if (password_verify($password, $usuario->password)) {
                $token = random_bytes(100);
                $result = $this->db->query("
                    INSERT INTO token_usuarios VALUES
                    (?, ?, NULL)
                ", array($token, $usuario->id));
                if ($result) {
                    return $token;
                }
            }
        }
    }
    public function login_token($token) {
        $query = $this->db->query("
            SELECT * FROM token_usuarios
            WHERE token_id = ?
            AND NOW() > token_create
            AND NOW() < DATE_ADD(token_create, INTERVAL 45 MINUTE)
        ", array($token));
        if ($query->num_rows() == 1) {
            $query1 = $this->db->query("
                SELECT * FROM usuarios
                WHERE id = ?
            ", array($query->row(0)->user_id));
            return $query1->row(0);
        }
    }
}
