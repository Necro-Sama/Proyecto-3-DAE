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
    public function bloquearHorario()
    {
        // Obtener datos desde el cuerpo de la solicitud
        $input = json_decode($this->input->raw_input_stream, true);

        $id = $input['id'];
        $fechaInicio = $input['fechaInicio'];
        $fechaFinal = $input['fechaFinal'];
        $runUsuario = $this->session->userdata('RUN'); // Obtener el RUN del usuario actual

        // Verificar si el bloque ya está bloqueado
        $existe = $this->CitasModel->verificarBloque($id, $fechaInicio);

        if ($existe) {
            // Responder si el bloque ya estaba bloqueado
            echo json_encode(['success' => false, 'mensaje' => 'El bloque ya está bloqueado.']);
        } else {
            // Insertar el bloque bloqueado
            $this->Horario_model->bloquearHorario($runUsuario, $dia, $id, $fechaInicio, $fechaFinal);
            echo json_encode(['success' => true, 'mensaje' => 'Bloque bloqueado con éxito.']);
        }
    }

    public function seleccionarfecha($id) {

    
        //la idea principal es enviarlo a la agenda para que seleccione otro dia de las 3 semanas 
        //de esta manera cambiamos el boton de agendar por uno que diga reagendar de la misma manera que bloquear con un if
        //traspasamos una variable para que pueda reconocer el proceso
        //una vez se seleccione llamara a la funcion reagendar para agedar y eliminar la otra pasando los datos de la eliminacion
        //para la funcion eliminar cita y reusar funciones

        $RUN_usuario = $this->UserController->check_logged_in();
        $data = $this->comprobardatos($RUN_usuario);
        $data['reagenda'] = true;
        $data['eliminar'] = $id;
        $this->load->view('StudentAgendarView',$data);
        
    }
    public function reagendar(){
        //llamara a eliminar cita para borrar la anterior y pasara a tomar la nueva una vez terminado enviara un mensaje de reagendado con exito.

    }
}