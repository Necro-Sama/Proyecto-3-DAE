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
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        header('Content-Type: application/json');
        
        try {
            log_message('debug', '[INICIO] bloquear_individual');
            
            // Obtener datos
            $bloque_id = trim($this->input->post('ID'));
            $run_ts = trim($this->input->post('RUNTS'));
            $fecha_inicio = trim($this->input->post('FechaInicio'));
            $fecha_termino = trim($this->input->post('FechaTermino'));

            log_message('debug', 'Datos recibidos en controlador: ' . json_encode([
                'ID' => $bloque_id,
                'RUNTS' => $run_ts,
                'FechaInicio' => $fecha_inicio,
                'FechaTermino' => $fecha_termino
            ]));

            // Validación
            if (empty($bloque_id) || empty($run_ts) || 
                empty($fecha_inicio) || empty($fecha_termino)) {
                throw new Exception('Datos incompletos para el bloqueo');
            }

            $this->load->model('BloqueModel');

            $resultado = $this->BloqueModel->bloquear_horario([
                'ID' => $bloque_id,
                'RUNTS' => $run_ts,
                'FechaInicio' => $fecha_inicio,
                'FechaTermino' => $fecha_termino
            ]);

            if (!$resultado) {
                $last_error = $this->db->error();
                log_message('error', 'Error de base de datos: ' . json_encode($last_error));
                throw new Exception('Error al realizar el bloqueo: ' . $last_error['message']);
            }

            log_message('debug', '[FIN] bloquear_individual - Éxito');

            echo json_encode([
                'success' => true,
                'message' => 'Bloque bloqueado correctamente'
            ]);
            
        } catch (Exception $e) {
            log_message('error', 'Error en bloquear_individual: ' . $e->getMessage());
            
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
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
        // Asegurarse de que es una petición AJAX
        if (!$this->input->is_ajax_request()) {
            exit(json_encode(['status' => 'error', 'mensaje' => 'Petición no válida']));
        }

        // Limpiar cualquier salida previa
        while (ob_get_level()) {
            ob_end_clean();
        }

        // Establecer headers
        header('Content-Type: application/json');

        try {
            // Obtener datos POST
            $fecha_inicio = $this->input->post('fecha_inicio');
            $fecha_fin = $this->input->post('fecha_fin');
            $run_ts = $this->input->post('run_ts');

            // Log para debugging
            log_message('debug', sprintf(
                'Verificando bloque - Inicio: %s, Fin: %s, RUN: %s',
                $fecha_inicio,
                $fecha_fin,
                $run_ts
            ));

            // Validar datos
            if (empty($fecha_inicio) || empty($fecha_fin) || empty($run_ts)) {
                exit(json_encode([
                    'status' => 'error',
                    'mensaje' => 'Faltan datos requeridos'
                ]));
            }

            // Cargar modelo si no está cargado
            if (!isset($this->BloqueModel)) {
                $this->load->model('BloqueModel');
            }

            // Verificar disponibilidad
            $bloque_existente = $this->BloqueModel->verificar_bloque_disponible(
                $fecha_inicio,
                $fecha_fin,
                $run_ts
            );

            // Enviar respuesta
            exit(json_encode([
                'status' => 'success',
                'disponible' => !$bloque_existente,
                'mensaje' => !$bloque_existente ? 
                    'Bloque disponible' : 
                    'Este trabajador social ya tiene bloqueado este horario'
            ]));

        } catch (Exception $e) {
            log_message('error', 'Error en verificación: ' . $e->getMessage());
            exit(json_encode([
                'status' => 'error',
                'mensaje' => 'Error al verificar disponibilidad: ' . $e->getMessage()
            ]));
        }
    }
}