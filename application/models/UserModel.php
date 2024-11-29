<?php
require_once FCPATH.'google/vendor/autoload.php';
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
            SELECT * FROM persona
            WHERE Correo = ?
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
        if (!password_verify($password, $usuario->Contraseña)) {
            $this->session->error = "Correo o contraseña incorrectos.";
            return;
        }
        $new_token = random_bytes(100);
        $insert_new_token_result = $this->db->query("
            INSERT INTO cookie VALUES
            (NULL, ?, NULL, ?)
        ", array($new_token, $usuario->RUN));
        if (!$insert_new_token_result) {
            $this->session->error = "Error interno en el servidor a la hora de generar el token de acceso.";
            return;
        }
        sleep(1);
        return $new_token;
    }
    public function login_token($token) {
        $query = $this->db->query("
            SELECT * FROM cookie
            WHERE Token = ?
            AND NOW() > FechaCreacion
            AND NOW() < DATE_ADD(FechaCreacion, INTERVAL 30 MINUTE)
        ", array($token));
        if ($query->num_rows() == 1) {
            $query1 = $this->db->query("
                SELECT * FROM persona
                WHERE RUN = ? 
            ", array($query->row(0)->RUN));
            return $query1->row(0)->RUN;
        } elseif ($query->num_rows() > 1) {
            $this->session->error = "Usuario ya logeado.";
        } else {
            $this->session->error = "Token expirado.";
        }
    }
    public function logout($token) {
        $id_usuario = $this->db->query("
            DELETE FROM cookie
            WHERE RUN = (SELECT RUN FROM (SELECT t1.RUN, t1.Token FROM cookie t1
                                          WHERE t1.Token = ?) t2)
        ", array($token));
        session_destroy();
    }
    public function get_funcionario($RUN) {
        $query = $this->db->query("
            SELECT * FROM funcionario
            WHERE RUN = ?
        ", array($RUN));
        if (!$query->num_rows()) {
            return;
        }
        return $query->row(0);
    }
    public function get_RUN_from_email($email) {
        $query = $this->db->query("
            SELECT * FROM persona
            WHERE Correo = ?
        ", array($email));
        if (!$query->num_rows()) {
            return;
        }
        return $query->row(0)->RUN;
    }
    public function get_trabajador_social($RUN) {
        $query = $this->db->query("
            SELECT * FROM trabajadorsocial
            WHERE RUN = ?
        ", array($RUN));
        if (!$query->num_rows()) {
            return;
        }
        return $query->row(0);
    }
    public function get_admin($RUN)
    {
        $query = $this->db->query("
            SELECT * FROM administrador
            WHERE RUN = ?    
        ",array($RUN));
        
        return $query->num_rows() > 0;
    }

    public function get_estudiante($RUN) {
        $query = $this->db->query("
            SELECT e.COD_CARRERA, p.* FROM persona p, estudiante e
            WHERE p.RUN = e.RUN
            AND p.RUN = ?
        ", array($RUN));
        if (!$query->num_rows()) {
            return;
        }
        return $query->row(0);
    }
    public function get_no_estudiante($RUN) {
        $query = $this->db->query("
            SELECT * FROM noestudiante
            WHERE RUN = ?
        ", array($RUN));
        if (!$query->num_rows()) {
            return;
        }
        return $query->row(0);
    }
    public function check_google_logged_in($id_token) {
        $CLIENT_ID = "937712052910-utrla4pp1g3pnhcpfn00gi5j01eio5fj.apps.googleusercontent.com";
        $client = new Google_Client(['client_id' => $CLIENT_ID]);  // Specify the CLIENT_ID of the app that accesses the backend
        $payload = $client->verifyIdToken($id_token);
        if ($payload) {
            // $userid = $payload['sub'];
            $email = $payload['email'];
            $email_verified = $payload['email_verified'];
            if (!$email_verified) {
                return;
            }
            if (!$this->endsWith($email, "uta.cl")) {
                $this->session->error = "Cuenta de google no es institucional.";
                return;
            }
            $RUN = $this->get_RUN_from_email($email);
            if (!$RUN) {
                $this->session->error = "Cuenta de google no registrada.";
                return;
            }
            return $RUN;
        } else {
            // Invalid ID token
            $this->session->error = "Hubo un error con esa cuenta de google.";
            return;
        }
    }
    function endsWith( $haystack, $needle ) {
        $length = strlen( $needle );
        if( !$length ) {
            return true;
        }
        return substr( $haystack, -$length ) === $needle;
    }
    public function getPersona($run) {
        // Obtener datos básicos de la tabla `persona`
        $query = $this->db->get_where('persona', ['RUN' => $run]);
        return $query->row_array();
    }

    public function getEstudiante($run) {
        // Obtener datos adicionales de estudiante
        $this->db->select('c.COD_CARRERA,c.Nombre');
        $this->db->from('estudiante e');
        $this->db->join('carrera c', 'e.COD_CARRERA = c.COD_CARRERA');
        $this->db->where('e.RUN', $run);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getFuncionario($run) {
        // Obtener datos adicionales de funcionario
        $this->db->select('*');
        $this->db->from('trabajadorsocial');
        $this->db->where('RUN', $run);
        $query = $this->db->get();
        return "Trabajador Social";
    }

    public function getAdministrador($run) {
        // Obtener datos adicionales de administrador
        $this->db->select('*');
        $this->db->from('administrador');
        $this->db->where('RUN', $run);
        $query = $this->db->get();
        return $query->row_array();
    }
}
class Trabajadores_model extends CI_Model {
    // Seccion licencias
    public function obtenerTrabajadoresSociales() {
        $query = $this->db->query('
        SELECT p.Nombre,p.Apellido,p.RUN FROM persona p
        join trabajadorsocial t
        on (p.RUN = t.RUN)
        '); 
        $result = $query->result_array();
        return $result;
    }

    public function guardarLicencia($run, $fecha_inicio, $fecha_termino) {
        $data = [
            'RUN' => $run,
            'FECHA_INI' => $fecha_inicio,
            'FECHA_TER' => $fecha_termino,
        ];
        if($this->ConsultaEstado($run)){
            $this->db->query('
            UPDATE persona  set Activo = 0 where RUN = ?
            ', array($run));
        }
        return $this->db->insert('Licencia', $data);
    }
    public function ConsultaEstado($run){
    
        return $this->db->query('
        SELECT Activo from persona
        where RUN =?
        ',array($run));
    }
}