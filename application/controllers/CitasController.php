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
    public function bloquear() {
        header('Content-Type: application/json');
        
        try {
            // Validar datos requeridos
            $requiredFields = ['ID', 'run_trabajador', 'fechainicio', 'fechafinal'];
            foreach ($requiredFields as $field) {
                if (!$this->input->post($field)) {
                    throw new Exception('Datos incompletos: falta ' . $field);
                }
            }

            $data = array(
                'ID' => $this->input->post('ID'),
                'RUN' => $this->input->post('run_trabajador'),
                'fechainicio' => $this->input->post('fechainicio'),
                'fechafinal' => $this->input->post('fechafinal')
            );

            // Log para debug
            log_message('debug', 'Datos a insertar en bloquebloqueado: ' . json_encode($data));

            $this->load->model('CitasModel');
            $resultado = $this->CitasModel->insertarBloqueBloqueado($data);

            if ($resultado) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Bloque bloqueado correctamente'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'No se pudo bloquear el bloque'
                ]);
            }

        } catch (Exception $e) {
            log_message('error', 'Error en bloquear: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    //seccion reagendar
    public function obtener_horarios() {
        $start = $this->input->get('start');
        $end = $this->input->get('end');
        
        $horarios = $this->CitasModel->obtenerHorariosEnRango($start, $end);
        
        header('Content-Type: application/json');
        echo json_encode($horarios);
    }
    public function verificar_disponibilidad() {
        header('Content-Type: application/json');
        
        try {
            // Obtener y validar datos
            $fechainicio = $this->input->post('fechainicio');
            $rut_trabajador = $this->input->post('rut_trabajador');

            // Log para debug
            log_message('debug', 'Verificando disponibilidad - Fecha: ' . $fechainicio . ' RUT: ' . $rut_trabajador);

            if (empty($fechainicio) || empty($rut_trabajador)) {
                throw new Exception('Datos incompletos para verificar disponibilidad');
            }

            // Cargar el modelo si no está cargado
            if (!isset($this->CitasModel)) {
                $this->load->model('CitasModel');
            }

            // Verificar disponibilidad
            $disponible = $this->CitasModel->verificarDisponibilidadBloque($fechainicio, $rut_trabajador);
            
            log_message('debug', 'Resultado verificación: ' . ($disponible ? 'Disponible' : 'No disponible'));

            echo json_encode([
                'success' => true,
                'disponible' => $disponible
            ]);

        } catch (Exception $e) {
            log_message('error', 'Error en verificar_disponibilidad: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
                'error' => true
            ]);
        }
    }
    public function obtener_bloques_bloqueados() {
        header('Content-Type: application/json');
        
        try {
            $fecha = $this->input->post('fecha');
            $rut_trabajador = $this->input->post('rut_trabajador');

            if (!$fecha || !$rut_trabajador) {
                throw new Exception('Datos incompletos');
            }

            $this->load->model('CitasModel');
            $bloques = $this->CitasModel->obtenerBloquesBloqueados($fecha, $rut_trabajador);

            echo json_encode([
                'success' => true,
                'bloques' => $bloques
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function desbloquear() {
        header('Content-Type: application/json');
        
        try {
            $id = $this->input->post('ID');
            $runts = $this->input->post('RUNTS');

            if (!$id || !$runts) {
                throw new Exception('Datos incompletos');
            }

            $this->load->model('CitasModel');
            $resultado = $this->CitasModel->desbloquearBloque($id, $runts);

            echo json_encode([
                'success' => $resultado,
                'message' => $resultado ? 'Bloque desbloqueado correctamente' : 'No se pudo desbloquear el bloque'
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function bloquear_dia_completo() {
        header('Content-Type: application/json');
        
        try {
            // Validar datos requeridos
            $requiredFields = ['ID', 'run_trabajador', 'fechainicio', 'fechafinal'];
            foreach ($requiredFields as $field) {
                if (!$this->input->post($field)) {
                    throw new Exception('Datos incompletos: falta ' . $field);
                }
            }

            $data = array(
                'ID' => $this->input->post('ID'),
                'RUN' => $this->input->post('run_trabajador'),
                'fechainicio' => $this->input->post('fechainicio'),
                'fechafinal' => $this->input->post('fechafinal')
            );

            $this->load->model('CitasModel');
            $resultado = $this->CitasModel->insertarBloqueDiaCompleto($data);

            if ($resultado) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Día bloqueado correctamente'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'No se pudo bloquear el día'
                ]);
            }

        } catch (Exception $e) {
            log_message('error', 'Error en bloquear_dia_completo: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function bloquear_individual() {
        header('Content-Type: application/json');
        
        try {
            // Obtener y validar los datos
            $runTS = $this->input->post('RUNTS');
            $fechaInicio = $this->input->post('FechaInicio');
            $fechaFinal = $this->input->post('FechaTermino');
            $id = $this->input->post('ID');

            // Log para debug
            log_message('debug', 'Datos recibidos en bloquear_individual: ' . 
                json_encode([
                    'RUNTS' => $runTS,
                    'FechaInicio' => $fechaInicio,
                    'FechaTermino' => $fechaFinal,
                    'ID' => $id
                ])
            );

            // Validar datos requeridos
            if (!$runTS || !$fechaInicio) {
                throw new Exception('Datos incompletos: Se requiere RUN del trabajador social y fecha de inicio');
            }

            $data = array(
                'ID' => $id ?: 'BLQ' . time() . rand(1000, 9999),
                'RUN' => $runTS,
                'fechainicio' => $fechaInicio,
                'fechafinal' => $fechaFinal ?: $fechaInicio
            );

            $this->load->model('CitasModel');
            $resultado = $this->CitasModel->insertarBloqueIndividual($data);

            echo json_encode([
                'success' => $resultado,
                'message' => $resultado ? 'Bloque bloqueado correctamente' : 'No se pudo bloquear el bloque'
            ]);

        } catch (Exception $e) {
            log_message('error', 'Error en bloquear_individual: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}