<?php
class TrabajadorSocialModel extends CI_Model {

    public function agregarPersona($personaData) {
        $this->db->insert('persona', $personaData);
        return $this->db->insert_id();  // Obtener el ID de Persona recién insertado
    }

    public function agregarTS($tsData) {
        $this->db->insert('trabajadorsocial', $tsData);
    }

    public function obtenerTS() {
        $this->db->select('trabajadorsocial.*, persona.Nombre, persona.Apellido, persona.Apellido, persona.Telefono, persona.Correo')
                 ->from('trabajadorsocial')
                 ->join('persona', 'trabajadorsocial.RUN = persona.RUN');
        return $this->db->get()->result_array();
    }
    

    public function obtenerTSPorID($id) {
        $this->db->select('trabajadorsocial.*, persona.Nombre, persona.Apellido')
                 ->from('trabajadorsocial')
                 ->join('persona', 'trabajadorsocial.RUN = persona.RUN')
                 ->where('trabajadorsocial.ID_TS', $id);
        return $this->db->get()->row_array();
    }

    public function editarPersona($run, $personaData) {
        $this->db->where('RUN', $run);
        $this->db->update('persona', $personaData);
    }

    public function editarTS($id, $tsData) {
        $this->db->where('ID_TS', $id);
        $this->db->update('trabajadorsocial', $tsData);
    }

    public function eliminarTS($id) {
        $this->db->where('ID_TS', $id);
        $this->db->delete('trabajadorsocial');
    }
}
