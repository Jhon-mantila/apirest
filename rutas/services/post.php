<?php
require_once "modelos/connection.php";
require_once "controlador/post.cotroller.php";

if(isset($_POST)){
    
    //echo '<pre>';print_r($_POST);echo '</pre>';
    
    $columns = array();

    foreach(array_keys($_POST) as $key => $value){
        //echo '<pre>';print_r($value);echo '</pre>';
        array_push($columns, $value);
    }
    //echo '<pre>';print_r($columns);echo '</pre>';

    //echo '<pre>';print_r(Conexion::getColumnsData($table, $columns));echo '</pre>';
    
    //Validar campos de la tablas con la columna si existe en la base de datos
    if(empty(Conexion::getColumnsData($table, $columns))){
        $json = array(
            'status' => 400,
            'Results' => 'Error Fields in the form do not match the database'

        );
        Conexion::logJsonControlados($json);
        echo json_encode($json, http_response_code($json['status']));

        return;
    }

    $response = new PostController();
  

    //Petición registro de usuarios
    if(isset($_GET['register']) && $_GET['register'] == true){

        $suffix = $_GET['suffix'] ?? "client";

        $response->postRegister($table,$_POST, $suffix);

    }else if(isset($_GET['login']) && $_GET['login'] == true){

        $suffix = $_GET['suffix'] ?? "client";
        //Peticon para token 
        $response->postLogin("clients",$_POST, $suffix);

    }else{
        //validar peticiones para usuarios autorizados

        if(isset($_GET["token"])){

            $tableToken = $_GET['table'] ?? "clients";
            $suffix = $_GET['suffix'] ?? "client";
            //Verificar si el token no ha expirado devuelve : "vigente_token", "expiro_token", "no_existe_token"
            $validate = Conexion::tokenValidate($_GET['token'], $tableToken, $suffix);
            
                //Token valido
                if($validate == "vigente_token"){
                    //Peticones crear registro cualquier tabla
                    $response->postData($table, $_POST, $suffix);
                
                    //echo '<pre>';print_r($_POST);echo '</pre>';

                    //return;
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

    }

    
}
?>