<?php

class CalendarioController extends CI_Controller {

    public function actualizar_fechas() {
        // Verificar si es administrador
        if ($this->session->userdata('tipo') !== 'administrador') {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode(['success' => false, 'message' => 'No autorizado']));
        }

        $duracion_bloque = $this->input->post('duracion_bloque');
        $semana_inicio = $this->input->post('semana_inicio');
        $cantidad_semanas = $this->input->post('cantidad_semanas');
        $mantener_citas = $this->input->post('mantener_citas') === 'on';

        try {
            // Convertir semana a fecha
            $fecha_inicio = new DateTime($semana_inicio);
            $fecha_fin = clone $fecha_inicio;
            $fecha_fin->modify('+' . ($cantidad_semanas * 7 - 1) . ' days');

            $this->db->trans_start();

            // Si no se mantienen las citas, eliminar solo los bloques sin citas
            if (!$mantener_citas) {
                $this->db->where('FechaInicio >=', $fecha_inicio->format('Y-m-d'))
                         ->where('FechaInicio <=', $fecha_fin->format('Y-m-d'))
                         ->where('Estado', 'disponible') // Solo eliminar bloques disponibles
                         ->delete('bloqueatencion');
            }

            // Crear nuevos bloques
            $hora_inicio = new DateTime('08:30'); // Hora de inicio del día
            $hora_fin = new DateTime('17:30');    // Hora de fin del día
            $intervalo = new DateInterval('PT' . $duracion_bloque . 'M');

            $fecha_actual = clone $fecha_inicio;
            while ($fecha_actual <= $fecha_fin) {
                // Solo días de semana (Lunes a Viernes)
                if ($fecha_actual->format('N') <= 5) {
                    $hora = clone $hora_inicio;
                    while ($hora < $hora_fin) {
                        // Verificar si ya existe una cita en este horario
                        $existe_cita = $this->db->where('FechaInicio', $fecha_actual->format('Y-m-d') . ' ' . $hora->format('H:i:s'))
                                              ->where('Estado !=', 'disponible')
                                              ->get('bloqueatencion')
                                              ->num_rows() > 0;

                        if (!$existe_cita) {
                            $data = [
                                'FechaInicio' => $fecha_actual->format('Y-m-d') . ' ' . $hora->format('H:i:s'),
                                'FechaTermino' => $fecha_actual->format('Y-m-d') . ' ' . $hora->add($intervalo)->format('H:i:s'),
                                'Estado' => 'disponible',
                                'Duracion' => $duracion_bloque
                            ];
                            $this->db->insert('bloqueatencion', $data);
                        }
                    }
                }
                $fecha_actual->modify('+1 day');
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Error al actualizar el calendario');
            }

            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode([
                                    'success' => true,
                                    'message' => 'Calendario actualizado correctamente'
                                ]));

        } catch (Exception $e) {
            return $this->output->set_content_type('application/json')
                                ->set_output(json_encode([
                                    'success' => false,
                                    'message' => $e->getMessage()
                                ]));
        }
    }
} 