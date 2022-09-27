<?php
require_once 'controlador/get.controller.php';
//echo '<pre>';print_r($routesArray[1]);echo '</pre>';

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
    
    //validar peticiones para usuarios autorizados
    if(isset($_GET["token"])){
        //echo '<pre>';print_r($campo);echo '</pre>';
        //echo '<pre>';print_r($value);echo '</pre>';
    $tableToken = $_GET['table'] ?? "clients";
    $suffix = $_GET['suffix'] ?? "client";
    //echo '<pre>';print_r($_GET["token"]);echo '</pre>';
    //echo '<pre>';print_r($_GET["table"]);echo '</pre>';
    //echo '<pre>';print_r($_GET["suffix"]);echo '</pre>';
    //Verificar si el token no ha expirado devuelve : "vigente_token", "expiro_token", "no_existe_token"
    $validate = Conexion::tokenValidate($_GET['token'], $tableToken, $suffix);
    
                    //Token valido
                    if($validate == "vigente_token"){

                    $response->getDataFilter($table, $select, $campo, $value);

                    }
                    //Error token expiro
                    if($validate == "expiro_token"){
                    
                            $json = array(
                                'status' => 303,
                                'Results' => 'Error: the token has expired'
                    
                            );
                    
                            echo json_encode($json, http_response_code($json['status']));
                    
                            return; 
                        
                    }
        
                    //Error token no coincide en base de datos 
                    if($validate == "no_existe_token"){
                            
                            $json = array(
                                'status' => 400,
                                'Results' => 'Error: the token not auth'
                    
                            );
                    
                            echo json_encode($json, http_response_code($json['status']));
                    
                            return; 
        
                    }
                    
     //Error no suministraron token 
    }else{
            
        $json = array(
            'status' => 400,
            'Results' => 'Error: auth required'

        );

        echo json_encode($json, http_response_code($json['status']));

        return; 
}                

//Buscar por porcentaje
}elseif(isset($_GET['name']) && isset($_GET["porcentaje"])){
   
    $name = $_GET['name'];
    $porcentaje = $_GET['porcentaje'];
    //echo '<pre>';print_r($name);echo '</pre>';
    //echo '<pre>';print_r($porcentaje);echo '</pre>';

    //validar peticiones para usuarios autorizados
   if(isset($_GET["token"])){
   
    $tableToken = $_GET['table'] ?? "clients";
    $suffix = $_GET['suffix'] ?? "client";
    //Verificar si el token no ha expirado devuelve : "vigente_token", "expiro_token", "no_existe_token"
    $validate = Conexion::tokenValidate($_GET['token'], $tableToken, $suffix);

                    //Token valido
                    if($validate == "vigente_token"){

                        $response->getDataPorcentaje($select, $name, $porcentaje);
                    }
                    
                    //Error token expiro
                    if($validate == "expiro_token"){
                    
                                $json = array(
                                    'status' => 303,
                                    'Results' => 'Error: the token has expired'
                        
                                );
                        
                                echo json_encode($json, http_response_code($json['status']));
                        
                         return; 
                            
                    }
            
                    //Error token no coincide en base de datos 
                    if($validate == "no_existe_token"){
                                
                                $json = array(
                                    'status' => 400,
                                    'Results' => 'Error: the token not auth'
                        
                                );
                        
                                echo json_encode($json, http_response_code($json['status']));
                        
                        return; 
            
                    }                
    
    //Error no suministraron token 
    }else{
                
        $json = array(
            'status' => 400,
            'Results' => 'Error: auth required'

        );

        echo json_encode($json, http_response_code($json['status']));

        return; 
    }                    

}else{
    //Encaso de buscar con get sin filtro (where)
    $response->getData($table, $select);
}






?>