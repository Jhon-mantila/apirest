<?php
require_once 'modelos/get.model.php';
require_once 'modelos/connection.php';
class GetController{

    static public function getData($table, $select){

        $response = GetModel::getData($table, $select);

        $return = new GetController();
        $return->fncResponse($response);

       //return $response;
    }
    //Encaso de buscar con get con filtro (where)
    public function getDataFilter($table, $select, $campo, $value){

        $response = GetModel::getDataFilter($table, $select, $campo, $value);
        $return = new GetController();
        $return->fncResponse($response);
    }

    //Encaso de buscar porcentaje por nombre
    public function getDataPorcentaje($select, $name, $porcentaje){

            $response = GetModel::getDataPorcentaje($select, $name, $porcentaje);
            //echo '<pre>';print_r($response);echo '</pre>';
            //return json_encode($response);
            $return = new GetController();
            $return->fncResponse($response);

    }
    //Respuesta del controlador get sin filtro

    public function fncResponse($response){
        
        if(!empty($response)){
           
            $json = array(
                'status' => 200,
                'total' => count($response),
                'result' => $response
            );

        }else{
            
            $json = array(
                'status' => 404,
                'total' => 0,
                'result' => 'No se encontraron resultados',
                'method' => 'get'
            );

            Conexion::logJsonControlados($json);
        }

        echo json_encode($json, http_response_code($json["status"]));
    }
}
?>