<?php
require_once "modelos/connection.php";
require_once "controlador/put.controller.php";
$table = explode("?", $routesArray[1])[0];
//echo '<pre>';print_r($table);echo '</pre>';
//return;
if(isset($_GET["id"]) && isset($_GET["nameId"])){
   // echo '<pre>';print_r($_GET["id"]);echo '</pre>';
   // echo '<pre>';print_r($_GET["nameId"]);echo '</pre>';

   //Capturar datos del formulario
   $data = array();

   //convertir una cadena de texto en un array
   parse_str(file_get_contents('php://input'), $data);
   //file_get_contents('php://input');
   //echo '<pre>';print_r(file_get_contents('php://input'));echo '</pre>';
   //echo '<pre>';print_r($data);echo '</pre>';

   //Separar las porpiedades de un array

   $columns = array();

   foreach(array_keys($data) as $key => $value){
    array_push($columns, $value);
   }

   array_push($columns, $_GET["nameId"]);
   $columns = array_unique($columns);
   //echo '<pre>';print_r($columns);echo '</pre>';

   //validar tablas y columnas
   if(empty(Conexion::getColumnsData($table, $columns))){


    $json = array(
        'status' => 400,
        'results' => 'Error Fields in the form do not match the database',
    );

    echo json_encode($json, http_response_code($json["status"]));

    return;
   }

   //echo '<pre>';print_r($data);echo '</pre>';

   //Solicitamos respuesta del controlador para editar datos en cualquier tabla

   $response = new PutController();
   $response->putData($table, $data, $_GET['id'], $_GET['nameId']);
}

?>