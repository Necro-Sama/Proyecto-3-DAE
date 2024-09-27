<?php
class UserModel extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    public function login($email, $password, $user_type) {
        $this->db->select("*");
        $this->db->from("usuarios");
        $this->db->where("email", $email);
        $this->db->where("user_type", $user_type);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            $usuario = $query->row(0);
            // echo $usuario->password;
            // echo password_hash("3333", PASSWORD_DEFAULT);
            if (password_verify($password, $usuario->password)) {
                return $usuario;
            }
        }
    }
}
