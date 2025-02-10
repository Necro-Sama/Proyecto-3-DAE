<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$route['default_controller'] = 'UserController/login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//Rutas de Usuario
    //Inicio de sesion
        $route['usuarios'] = 'UserController';
        $route['usuarios/login'] = 'UserController/login';
        $route['usuarios/logout'] = 'UserController/logout';
        $route['usuarios/auth'] = 'UserController/auth';
    //Registrar Usuario
    $route['usuarios/registrar']= 'UserController/registrar';
    //Home
        $route['usuarios/home'] = 'UserController/home';
//Gestion de Citas
    //Rutas de agendar cita
        $route['usuarios/agendar'] = 'UserController/agendar';
        $route['usuarios/accion_agendar'] = 'UserController/accion_agendar';
    //Rutas de reagendar cita
        $route['usuarios/vistaReagendar'] = 'CitasController/abrirreagendar';
    //Rutas de eliminar cita    
        $route['usuarios/eliminarcita'] = 'EliminarCitaController/eliminarCita';
    //Rutas de bloquear cita
        $route['citas/bloquear'] = 'CitasController/bloquear';
        $route['citas/verificar_disponibilidad'] = 'CitasController/verificar_disponibilidad';
        // Rutas para bloqueo/desbloqueo
        $route['citas/obtener_bloques_bloqueados'] = 'CitasController/obtener_bloques_bloqueados';
        $route['citas/desbloquear'] = 'CitasController/desbloquear';
        // Rutas para bloqueo de citas
        $route['citas/verificar_disponibilidad_bloque'] = 'CitasController/verificar_disponibilidad_bloque';
        $route['citas/bloquear_individual'] = 'CitasController/bloquear_individual';
        $route['citas/bloquear_dia_completo'] = 'CitasController/bloquear_dia_completo';
    //Rutas de marcar como atendida
        $route['trabajadorsocial/marcarComoAtendida'] = 'TrabajadorSocialController/marcarComoAtendida';
//Rutas de Gestion TS
    $route['usuarios/gestor_ts'] = 'TrabajadorSocialController/index';
    //gestion de ts
    $route['usuarios/TrabajadorSocialController/editar/:id']= 'TrabajadorSocialController/editar';
    $route['usuarios/TrabajadorSocialController/eliminar/:id']= 'TrabajadorSocialController/eliminar';
    $route['usuarios/TrabajadorSocialController/agregar']= 'TrabajadorSocialController/agregar';
    //Rutas asignar Carrera
        $route['usuarios/asignar-carrera'] = 'TrabajadorSocialController/asignarTSACarrera';
        $route['usuarios/asignar-carrera-procesar'] = 'TrabajadorSocialController/asignarTSACarreraProcesar';
    //Rutas Ingreso de Licencia
        $route['usuarios/Licencia'] = 'UserController/Licencia';
        $route['usuarios/guardar']= 'UserController/guardar';
//Ruta general vizualizar cita
    $route['usuarios/visualizar-citas'] = 'TrabajadorSocialController/obtenercita';

//Rutas de Estadísticas
$route['usuarios/estadisticas'] = 'EstadisticasController/EstadisticaView';
/**
 * Rutas para estadisticas
 */
$route['usuarios/estadisticas/obtenerDatos'] = 'EstadisticasController/obtenerDatos';
$route['usuarios/estadisticas/exportarPDF'] = 'EstadisticasController/exportarPDF';







