<?php
class User_model extends CI_Model {

    public function __construct() {
        $this->load->database();  // Cargar la base de datos
    }

    public function login($email, $password) {
        // Verificar si el usuario existe con email y contraseña
        $this->db->where('email', $email);
        $query = $this->db->get('usuarios');

        if ($query->num_rows() == 1) {
            $user = $query->row();
            // Verificar la contraseña (usando hash)
            if (password_verify($password, $user->password)) {
                return $user;
            }
        }
        return false;
    }
}
