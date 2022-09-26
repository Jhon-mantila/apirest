<?php
//echo '<pre>';print_r($_SERVER['REQUEST_URI']);echo '</pre>';
$routesArray = explode("/", $_SERVER['REQUEST_URI']);
$routesArray = array_filter($routesArray);
//echo '<pre>';print_r($routesArray);echo '</pre>';

//CUANDO NO SE HACE NINGUNA PETICIÓN A LA API.
if(empty($routesArray)){

    $json = array(
        'status' => 404,
        'result' => 'Not found'
    );
    
    echo json_encode($json, http_response_code($json["status"]));
    
    return;

}

//CUANDO SE HACE UNA PETICIÓN A LA API.

if(!empty($routesArray) && isset($_SERVER['REQUEST_METHOD'])){
    //echo '<pre>';print_r($_SERVER['REQUEST_METHOD']);echo '</pre>';
    //Petición GET
    if($_SERVER['REQUEST_METHOD'] == "GET"){

        include "rutas/services/get.php";
    }
    //Petición POST
    if($_SERVER['REQUEST_METHOD'] == "POST"){

        include "rutas/services/post.php";

    }
    //Petición UPDATE
    if($_SERVER['REQUEST_METHOD'] == "PUT"){
        
        include "rutas/services/put.php";

    }
    //Petición DELETE
    if($_SERVER['REQUEST_METHOD'] == "DELETE"){

        $json = array(
            'status' => 200,
            'result' => 'Peticion DELETE'
        );
        
        echo json_encode($json, http_response_code($json["status"]));
    }
    
}



?>