<?php
require_once 'controlador/get.controller.php';
//echo '<pre>';print_r($routesArray[1]);echo '</pre>';
//Separar tabla
$table = explode("?", $routesArray[1])[0];
//echo '<pre>';print_r($routesArray[1]);echo '</pre>';

//$select = "*";
$select = $_GET['select'] ?? "*";

$response = new GetController();

//Encaso de buscar con get con filtro (where)
if(isset($_GET['linkTo']) && isset($_GET["equalTo"])){
    $campo = $_GET['linkTo'];
    $value = $_GET["equalTo"];
    //echo '<pre>';print_r($campo);echo '</pre>';
    //echo '<pre>';print_r($value);echo '</pre>';
    
    $response->getDataFilter($table, $select, $campo, $value);

}elseif(isset($_GET['name']) && isset($_GET["porcentaje"])){
    $name = $_GET['name'];
    $porcentaje = $_GET['porcentaje'];
    //echo '<pre>';print_r($name);echo '</pre>';
    //echo '<pre>';print_r($porcentaje);echo '</pre>';

    $response->getDataPorcentaje($select, $name, $porcentaje);

}else{
    //Encaso de buscar con get sin filtro (where)
    $response->getData($table, $select);
}






?>