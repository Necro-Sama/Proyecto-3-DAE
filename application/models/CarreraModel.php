<?php
class CarreraModel extends CI_Model {

    public function obtenerCarreras() {
        
        $query = $this->db->get('carrera');
        return $query->result_array();
    }

    public function asignarTrabajadorSocialACarrera($cod_carrera, $run_ts_principal, $run_ts_reemplazo) {
        
        $data = [
            'RUNTS' => $run_ts_principal, 
            'ReemplazaRUNTS' => $run_ts_reemplazo 
        ];
    
        // Actualiza la carrera con el código especificado
        $this->db->where('COD_CARRERA', $cod_carrera);
        $this->db->update('carrera', $data);
    }
    
}

?>
