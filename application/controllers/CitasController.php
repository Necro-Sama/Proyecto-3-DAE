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
        $this->load->model('bloquemodel', 'BloqueModel');
        $this->output->set_content_type('application/json');
        $this->load->helper('url');
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
        // Deshabilitar cualquier salida previa
        if (ob_get_level()) ob_end_clean();
        
        try {
            $datos = $this->input->post();
            
            if (!$datos) {
                throw new Exception('No se recibieron datos');
            }
            
            log_message('debug', 'Datos recibidos en bloquear: ' . json_encode($datos));
            
            if (empty($datos['run_trabajador']) || empty($datos['fechainicio']) || empty($datos['fechafinal'])) {
                throw new Exception('Faltan datos requeridos');
            }

            $datosBloqueo = [
                'ID' => uniqid('BLQ_'),
                'RUN' => $datos['run_trabajador'],
                'fechainicio' => $datos['fechainicio'],
                'fechafinal' => $datos['fechafinal']
            ];

            $resultado = $this->BloqueModel->bloquear_horario($datosBloqueo);
            
            if (!$resultado['success']) {
                throw new Exception($resultado['message']);
            }

            echo json_encode([
                'success' => true,
                'message' => 'Bloque bloqueado correctamente',
                'data' => $resultado
            ]);

        } catch (Exception $e) {
            log_message('error', 'Error en bloquear: ' . $e->getMessage());
            
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit();
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
        // Desactivar el buffer de salida
        ob_clean();
        
        // Asegurar que la respuesta sea JSON
        header('Content-Type: application/json');
        
        try {
            // Log inicial
            log_message('debug', '=== Inicio bloquear_individual ===');
            
            // Obtener y validar datos
            $datos = [
                'ID' => $this->input->post('ID'),
                'RUN' => $this->input->post('RUN'),
                'FechaInicio' => $this->input->post('FechaInicio'),
                'FechaTermino' => $this->input->post('FechaTermino')
            ];

            // Validar datos
            foreach ($datos as $key => $value) {
                if (empty($value)) {
                    throw new Exception("El campo {$key} es requerido");
                }
            }

            // Cargar el modelo
            $this->load->model('BloqueModel');

            // Intentar el bloqueo dentro de una transacción
            $this->db->trans_begin();

            $resultado = $this->BloqueModel->bloquear_horario($datos);

            if ($resultado === false) {
                $this->db->trans_rollback();
                $error = $this->db->error();
                throw new Exception('Error de base de datos: ' . json_encode($error));
            }

            $this->db->trans_commit();

            echo json_encode([
                'success' => true,
                'message' => 'Bloque bloqueado correctamente'
            ]);

        } catch (Exception $e) {
            // Si hay una transacción activa, hacer rollback
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            }

            $error_db = $this->db->error();
            
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
                'error_details' => [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'db_error' => $error_db
                ]
            ]);
        }
        
        // Asegurar que no haya más salida después de la respuesta JSON
        exit();
    }
    public function obtener_datos_ts($run) {
        // Verificar si es una petición AJAX
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        try {
            // Cargar el modelo necesario
            $this->load->model('TrabajadorSocialModel');
            
            // Obtener datos del TS
            $datos = $this->TrabajadorSocialModel->obtenerTrabajadorSocialPorRUN($run);
            
            // Enviar respuesta
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'data' => $datos
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function verificar_disponibilidad_dia() {
        try {
            $run_trabajador = $this->input->post('run_trabajador');
            $fecha = $this->input->post('fecha');

            // Verificar si hay bloques existentes para ese día y TS
            $bloques_existentes = $this->BloqueModel->obtener_bloques_por_dia_ts($run_trabajador, $fecha);

            $response = [
                'disponible' => count($bloques_existentes) === 0,
                'mensaje' => count($bloques_existentes) === 0 ? 
                            'Día disponible' : 
                            'El trabajador social ya tiene bloques en este día'
            ];

            header('Content-Type: application/json');
            echo json_encode($response);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'disponible' => false,
                'mensaje' => $e->getMessage()
            ]);
        }
    }
    public function verificar_disponibilidad_bloque() {
        try {
            $fechaInicio = $this->input->post('fechainicio');
            $fechaFinal = $this->input->post('fechafinal');
            $runTrabajador = $this->input->post('run_trabajador');

            // Verificar si hay citas existentes
            $disponible = $this->BloqueModel->verificar_disponibilidad([
                'fechainicio' => $fechaInicio,
                'fechafinal' => $fechaFinal,
                'RUNTS' => $runTrabajador
            ]);

            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => true,
                    'disponible' => $disponible
                ]));

        } catch (Exception $e) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]));
        }
    }
}