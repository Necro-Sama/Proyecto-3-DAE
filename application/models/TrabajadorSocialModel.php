<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TrabajadorSocialModel extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Método para obtener todos los trabajadores sociales con su información personal
    public function obtenerTrabajadoresSociales() {
        $this->db->select('trabajadorsocial.RUN, persona.Nombre, persona.Apellido, persona.Correo, persona.Telefono, 
                           IF(administrador.RUN IS NOT NULL, 1, 0) as is_admin');
        $this->db->from('trabajadorsocial');
        $this->db->join('funcionario', 'trabajadorsocial.RUN = funcionario.RUN', 'inner');
        $this->db->join('persona', 'funcionario.RUN = persona.RUN', 'inner');
        $this->db->join('administrador', 'persona.RUN = administrador.RUN', 'left'); // Join con la tabla 'administrador'
        
        return $this->db->get()->result_array(); 
    }
    
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

    // Método para agregar un trabajador social
    public function agregarTrabajadorSocial($datosPersona, $datosTrabajadorSocial) {
        // Primero, insertar en la tabla `persona`
        $this->db->insert('persona', $datosPersona);

        // Luego, insertar en la tabla `funcionario`
        $datosFuncionario = ['RUN' => $datosPersona['RUN']];
        $this->db->insert('funcionario', $datosFuncionario);

        // Finalmente, insertar en la tabla `trabajadorsocial`
        $datosTrabajadorSocial['RUN'] = $datosPersona['RUN'];
        return $this->db->insert('trabajadorsocial', $datosTrabajadorSocial);
    }

    // Método para actualizar un trabajador social
    public function actualizarTrabajadorSocial($RUN, $datosPersona) {
        // Actualizar la tabla `persona`
        $this->db->where('RUN', $RUN);
        $this->db->update('persona', $datosPersona);
    }

    // Método para eliminar un trabajador social
    public function eliminarTrabajadorSocial($RUN) {
        // Eliminar de la tabla `trabajadorsocial`
        $this->db->where('RUN', $RUN);
        $this->db->delete('trabajadorsocial');

        // Eliminar de la tabla `funcionario`
        $this->db->where('RUN', $RUN);
        $this->db->delete('funcionario');

        // Finalmente, eliminar de la tabla `persona`
        $this->db->where('RUN', $RUN);
        $this->db->delete('persona');
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
        // borrara a la TS de la lista y de la BD desde la raiz persona (datos personales basicos)
        $this->db->where('RUN', $id);
        $this->db->delete('trabajadorsocial');
        $this->db->delete('funcionario');
        $this->db->delete('persona');
    }
    // public function obtenerCita(){
    //     $query =$this->db->query('
    //     SELECT b.Estado, b.Motivo,bl.FechaInicio,bl.FechaTermino, p.RUN, p.Nombre, p.Apellido, p.Telefono, p.Correo 
    //     FROM bloqueatencion b 
    //     join persona p
    //     on (b.RUNCliente = p.RUN)
    //     join bloque bl
    //     on (b.ID = bl.ID)
    //     ');
    //     return $query->result_array();
    // }
    // public function obtenerCitaEstudiante($RUNTS,$RUNU){
    //     $query =$this->db->query('
    //     SELECT p.Nombre, p.Apellido, p.Telefono, p.Correo 
    //     FROM bloqueatencion b 
    //     join persona p
    //     on (b.RUNCliente = p.RUN)
    //     join bloque bl
    //     on (b.ID = bl.ID)
    //     WHERE RUNCliente = ? AND RUNTS = ?
    //     ',array($RUNU, $RUNTS));
    //     return $query->result_array();
    // }

    public function obtenerCitasAdministrador()
    {
        $query = $this->db->query('
            SELECT 
                bl.FechaInicio, bl.FechaTermino, 
                p.Nombre AS NombreEstudiante, p.Apellido AS ApellidoEstudiante, p.Telefono, p.Correo, 
                ts.Nombre AS NombreTS, ts.Apellido AS ApellidoTS, ts.Telefono AS TelefonoTS, ts.Correo AS CorreoTS,
                b.Motivo
            FROM bloqueatencion b
            JOIN persona p ON b.RUNCliente = p.RUN
            JOIN bloque bl ON b.ID = bl.ID
            JOIN persona ts ON bl.RUNTS = ts.RUN
        ');

        return $query->result_array();
    }

    public function obtenerCitasPorTS($RUNTS)
{
    $query = $this->db->query('
        SELECT 
            bl.FechaInicio, bl.FechaTermino, 
            p.Nombre AS NombreEstudiante, p.Apellido AS ApellidoEstudiante, p.Telefono, p.Correo, 
            ts.Nombre AS NombreTS, ts.Apellido AS ApellidoTS, ts.Telefono AS TelefonoTS, ts.Correo AS CorreoTS,
            b.Motivo
        FROM bloqueatencion b
        JOIN persona p ON b.RUNCliente = p.RUN
        JOIN bloque bl ON b.ID = bl.ID
        JOIN persona ts ON bl.RUNTS = ts.RUN
        WHERE bl.RUNTS = ?
    ', [$RUNTS]);

    return $query->result_array();
}

    public function obtenerCitaEstudiante($RUNTS, $RUNU)
    {
        $query = $this->db->query('
            SELECT 
                bl.FechaInicio, bl.FechaTermino, 
                p.Nombre AS NombreEstudiante, p.Apellido AS ApellidoEstudiante, p.Telefono, p.Correo,
                ts.Nombre AS NombreTS, ts.Apellido AS ApellidoTS, ts.Telefono AS TelefonoTS, ts.Correo AS CorreoTS
            FROM bloqueatencion b
            JOIN persona p ON b.RUNCliente = p.RUN
            JOIN bloque bl ON b.ID = bl.ID
            JOIN persona ts ON bl.RUNTS = ts.RUN
            WHERE b.RUNCliente = ? AND bl.RUNTS = ?
        ', [$RUNU, $RUNTS]);

        return $query->result_array();
    }

    public function obtenerCitasNoEstudiante($RUNNoEstudiante)
    {
        $query = $this->db->query('
            SELECT 
                bl.FechaInicio, bl.FechaTermino, 
                p.Nombre AS NombreEstudiante, p.Apellido AS ApellidoEstudiante, p.Telefono, p.Correo,
                ts.Nombre AS NombreTS, ts.Apellido AS ApellidoTS, ts.Telefono AS TelefonoTS, ts.Correo AS CorreoTS
            FROM bloqueatencion b
            JOIN persona p ON b.RUNCliente = p.RUN
            JOIN bloque bl ON b.ID = bl.ID
            JOIN persona ts ON bl.RUNTS = ts.RUN
            WHERE b.RUNCliente = ?
        ', [$RUNNoEstudiante]);

        return $query->result_array();
    }


    public function obtenerRUNTS($RUN){
        $query =$this->db->query('
        SELECT b.RUNTS FROM bloque b
        join bloqueatencion bl
        on b.ID=bl.ID
        where bl.RUNCliente = ?
        ',array($RUN));
        $result = $query->row_array(); // Obtiene la primera fila como un arreglo asociativo
        return isset($result['RUNTS']) ? $result['RUNTS'] : null; // Retorna el valor de RUNTS o null si no existe
    }
    public function esAdmin($run)
    {
        $this->db->select('RUN');
        $this->db->from('administrador');
        $this->db->where('RUN', $run);
        $query = $this->db->get();
        return $query->num_rows() > 0; // Retorna true si el RUN está como administrador
    }

    // Agregar el RUN a la tabla 'administrador'
    public function agregarAdmin($run)
    {
        $data = ['RUN' => $run];
        return $this->db->insert('administrador', $data);
    }

    // Eliminar el RUN de la tabla 'administrador'
    public function eliminarAdmin($run)
    {
        $this->db->where('RUN', $run);
        return $this->db->delete('administrador');
    }
}
?>
