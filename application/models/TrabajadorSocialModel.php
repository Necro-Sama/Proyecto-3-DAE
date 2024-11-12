<?php
class TrabajadorSocialModel extends CI_Model {

    public function agregarPersona($personaData) {
        $this->db->insert('Persona', $personaData);
        return $this->db->insert_id();  // Obtener el ID de Persona recién insertado
    }

    public function agregarTS($tsData) {
        $this->db->insert('TS', $tsData);
    }

    public function obtenerTS() {
        $this->db->select('TS.*, Persona.NOMBRE_PER, Persona.APELLID_PER, Persona.CORREO, Persona.TELEFONO')
                 ->from('TS')
                 ->join('Persona', 'TS.RUN = Persona.RUN');
        return $this->db->get()->result_array();
    }
    

    public function obtenerTSPorID($id) {
        $this->db->select('TS.*, Persona.NOMBRE_PER, Persona.APELLID_PER')
                 ->from('TS')
                 ->join('Persona', 'TS.RUN = Persona.RUN')
                 ->where('TS.ID_TS', $id);
        return $this->db->get()->row_array();
    }

    public function editarPersona($run, $personaData) {
        $this->db->where('RUN', $run);
        $this->db->update('Persona', $personaData);
    }

    public function editarTS($id, $tsData) {
        $this->db->where('ID_TS', $id);
        $this->db->update('TS', $tsData);
    }

    public function eliminarTS($id) {
        $this->db->where('ID_TS', $id);
        $this->db->delete('TS');
    }
}
