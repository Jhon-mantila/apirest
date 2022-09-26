<?php
//echo "Hola";
//Mostrar Errores

use FTP\Connection;

ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set("error_log", "C:/xampp/htdocs/apirest/php_error_log");

require_once "modelos/connection.php";
//Probando Conexión
//echo '<pre>';print_r(Conexion::connect());echo '</pre>';
//return;
//Requerimietos
require_once "controlador/routes.controller.php";

$index = new RoutesController();
$index->index();

?>