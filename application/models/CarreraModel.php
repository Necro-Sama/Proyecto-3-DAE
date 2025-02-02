<?php
class CarreraModel extends CI_Model {

    public function obtenerCarreras() {
        $this->db->select('c.*, 
                          CONCAT(p1.Nombre, " ", p1.Apellido) as NombreTS,
                          CONCAT(p2.Nombre, " ", p2.Apellido) as NombreReemplazo,
                          p1.Activo as EstadoTS,
                          p2.Activo as EstadoTSReemplazo');
        $this->db->from('carrera c');
        $this->db->join('persona p1', 'c.RUNTS = p1.RUN', 'left');
        $this->db->join('persona p2', 'c.ReemplazaRUNTS = p2.RUN', 'left');
        $query = $this->db->get();
        
        error_log("DEBUG - SQL Carreras: " . $this->db->last_query());
        $result = $query->result_array();
        error_log("DEBUG - Resultado Carreras: " . print_r($result, true));
        
        return $result;
    }

    public function obtenerTrabajadoresSocialesActivos() {
        $this->db->select('p.RUN, p.Nombre, p.Apellido, p.Activo,
                          (SELECT COUNT(*) FROM carrera WHERE RUNTS = p.RUN) as total_carreras_principal');
        $this->db->from('persona p');
        $this->db->join('funcionario f', 'p.RUN = f.RUN');
        $this->db->order_by('total_carreras_principal', 'ASC');
        $this->db->order_by('p.Apellido', 'ASC');
        
        $query = $this->db->get();
        
        error_log("DEBUG - SQL TS: " . $this->db->last_query());
        $result = $query->result_array();
        error_log("DEBUG - Resultado TS: " . print_r($result, true));
        
        return $result;
    }

    public function asignarTrabajadorSocialACarrera($cod_carrera, $run_ts_principal, $run_ts_reemplazo) {
        $data = [
            'RUNTS' => $run_ts_principal, 
            'ReemplazaRUNTS' => $run_ts_reemplazo 
        ];
    
        $this->db->where('COD_CARRERA', $cod_carrera);
        $this->db->update('carrera', $data);
    }
}
?>
