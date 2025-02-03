<?php

class CalendarioController extends CI_Controller {

    public function actualizar_fechas() {
        // Verificar si es administrador
        if ($this->session->userdata('tipo') !== 'administrador') {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['success' => false, 'message' => 'No autorizado']));
        }

        $semana_inicio = $this->input->post('semana_inicio');
        $cantidad_semanas = $this->input->post('cantidad_semanas');
        $mantener_citas = $this->input->post('mantener_citas') === 'on';

        try {
            // Convertir semana a fecha
            $fecha_inicio = new DateTime($semana_inicio);
            $fecha_fin = clone $fecha_inicio;
            $fecha_fin->modify('+' . ($cantidad_semanas * 7 - 1) . ' days');

            // Actualizar fechas en la base de datos
            $this->db->trans_start();
            
            if (!$mantener_citas) {
                // Eliminar citas existentes en el rango
                $this->db->where('FechaInicio >=', $fecha_inicio->format('Y-m-d'))
                         ->where('FechaInicio <=', $fecha_fin->format('Y-m-d'))
                         ->delete('bloqueatencion');
            }

            // Actualizar o insertar nuevos bloques
            // ... lógica para crear bloques en el nuevo rango de fechas ...

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Error al actualizar las fechas');
            }

            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['success' => true]));
        } catch (Exception $e) {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['success' => false, 'message' => $e->getMessage()]));
        }
    }
} 