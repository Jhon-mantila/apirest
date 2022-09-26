<?php
require_once "connection.php";
require_once "get.model.php";

class PutModel{

    //Petición editar datos
    static public function putData($table, $data, $id, $nameId){
        //echo '<pre>';print_r($id);echo '</pre>';
        //echo '<pre>';print_r($nameId);echo '</pre>';
        //echo '<pre>';print_r($data);echo '</pre>';
        //echo '<pre>';print_r($table);echo '</pre>';

        //validar el id si existe o no

        $response = GetModel::getDataFilter($table, $id, $nameId, $id);
        //echo '<pre>';print_r($response);echo '</pre>';
        //return;
        if(empty($response)){
            
            $response = array(
            
                "Results" => "No existe el $id"
            );
            
            $cadena = file_get_contents("C:/xampp/htdocs/apirest/php_error_log");
            $cadena .= "\r\n".implode(",", $response);
            file_put_contents("C:/xampp/htdocs/apirest/php_error_log", $cadena);

            return $response;

            //return null;
        }

        //Empieza la actualización
        $set = "";

        foreach($data as $key => $value){

            $set .= $key . " = :". $key . ",";
            //echo '<pre>';print_r($set);echo '</pre>';

        }
        //quitar la ultima ,
        $set = substr($set, 0,-1);
        //echo '<pre>';print_r($set);echo '</pre>';
        
        $sql = "UPDATE $table SET $set WHERE $nameId = :$nameId";
        $link = Conexion::connect();

        $stmt = $link->prepare($sql);

        //enlazar parametros

        foreach ($data as $key => $value){

            $stmt->bindParam(":".$key, $data[$key], PDO::PARAM_STR);
        }
        $stmt->bindParam(":".$nameId, $id, PDO::PARAM_STR);

        if($stmt->execute()){
            $response = array(
                "id"=> $id,
                "Results" => "The proccess was successful"
            );
            return $response;
        }else{
            $response = array(
                "id"=> $id,
                "Results" => "Not Fount"
            );
            return $response;
        }

    }
}
?>