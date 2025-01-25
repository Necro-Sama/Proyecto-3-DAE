<?php
defined("BASEPATH") or exit("No direct script access allowed");
class CitasController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('TrabajadorSocialModel');
        $this->load->model('CitasModel');
        $this->load->model('UserModel');
    }
    // Método para eliminar una cita
    public function eliminarCita()
    {
        // Obtener los datos desde el POST
        $idCita = $this->input->post('idCita');
        $runCliente = $this->input->post('runCliente');

        // Llamar al modelo para eliminar la cita
        $eliminado = $this->CitasModel->eliminarCita($idCita, $runCliente);

        if ($eliminado) {
            $this->session->set_flashdata('success', 'Cita cancelada correctamente.');
        } else {
            $this->session->set_flashdata('error', 'Hubo un error al intentar cancelar la cita.');
        }

        // Redirigir a la misma página
        redirect('usuarios/visualizar-citas');
    }
    function estadobloque($id_bloque){
        return $this->CitasModel->verificar_bloque($id_bloque);
    }
    public function check_logged_in()
    {
        if ($this->session->token) {
            return $this->UserModel->login_token($this->session->token);
        }
        $cred = $this->input->post("credential");
        $g_id_token = $cred ? $cred : $this->session->google_token;
        if (!$g_id_token) {
            return;
        }
        $g_client = $this->UserModel->check_google_logged_in($g_id_token);
        if ($g_client) {
            $this->session->google_token = $g_id_token;
            return $g_client;
        }
    }
    public function comprobardatos($RUN_usuario) {
        $data['persona'] = $this->UserModel->getPersona($RUN_usuario);
        
        if ($this->UserModel->getEstudiante($RUN_usuario)) {
            $data['tipo'] = 'estudiante';
            $data['detalle'] = $this->UserModel->getEstudiante($RUN_usuario);
        } 
        if ($this->UserModel->getFuncionario($RUN_usuario))  {
            $data['tipo'] = 'trabajadorsocial';
            $data['detalle'] = $this->UserModel->getFuncionario($RUN_usuario);
        } 
        if ($this->UserModel->getAdministrador($RUN_usuario)) {
            $data['tipo'] = 'administrador';
            $data['detalle'] = $this->UserModel->getAdministrador($RUN_usuario);
        } 
        else if ($this->UserModel->getNoEstudiante($RUN_usuario)) {
            $data['tipo'] = 'noestudiante'; 
            $data['detalle'] = $this->UserModel->getNoEstudiante( $RUN_usuario );
        }
        else{
            $data['tipo']='';
        }
        return $data;
    }
    public function bloquear()
    {
         
        $data = json_decode($this->input->raw_input_stream, true);
        log_message('debug', 'Datos recibidos: ' . print_r($data, true));
        $run = $data['run'];
        $id = $data['id'];
        $fechaInicio = $data['fechaInicio'];
        $fechaFinal = $data['fechaFinal'];

        $this->load->model('CitasModel');

        // Verificar si el bloque ya existe
        $existe = $this->CitasModel->verificarBloque($id, $fechaInicio, $fechaFinal);

        if ($existe) {
            echo json_encode([
                'success' => false,
                'message' => 'El bloque ya está bloqueado o asignado.'
            ]);
            return;
        }

        // Datos para insertar
        $insertData = [
            'run' => $run,
            'id_bloque' => $id,
            'fecha_inicio' => $fechaInicio,
            'fecha_final' => $fechaFinal,
        ];

        $result = $this->CitasModel->insertarBloqueBloqueado($insertData);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al insertar en la base de datos.'
            ]);
        }
    }
    //seccion reagendar
    public function abrirreagendar()
    {
        // Obtén el ID de la cita desde el formulario
        $idCita = $this->input->post('idCita');
        
        // Verifica si el usuario está autenticado
        $run = $this->check_logged_in();
        
        // Obtén los datos necesarios
        $data = $this->comprobardatos($run);
        $data['reagenda'] = true;
        $data['eliminar'] = $idCita;
        
        // Carga la vista de reagendar
        return $this->load->view('ReagendarView', $data);
    }
    public function reagendar(){
        //llamara a eliminar cita para borrar la anterior y pasara a tomar la nueva una vez terminado enviara un mensaje de reagendado con exito.

    }
}