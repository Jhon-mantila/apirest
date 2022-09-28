<?php
require_once "connection.php";

class GetModel{

    //Petición get porcentaje
    static public function getDataPorcentaje($select, $name, $porcentaje){
        
        $selectArray =  explode(",", $select);
        //validar existencia de la tabla y de las columnas

        //echo '<pre>';print_r(Conexion::getColumnsData($table, $selectArray));echo '</pre>';
        ///return;

        if(empty(Conexion::getColumnsData("files", $selectArray))){
            return null;
        }
        
        $response = array();
        $return = array();
        $contador = 0;
        //echo '<pre>';print_r($name);echo '</pre>';
        //echo '<pre>';print_r($porcentaje);echo '</pre>';
       
        $sql = "SELECT $select FROM files";
        
        $stmt = Conexion::connect()->prepare($sql);

        $stmt->execute();
    
       //echo '<pre>';print_r($stmt->fetchAll());echo '</pre>';
        
        foreach($stmt->fetchAll() as $row){
            //echo '<pre>';print_r($row['name_file']);echo '</pre>';
            similar_text($name, $row['name_file'], $percent);

            //echo '<pre>';print_r($percent);echo '</pre>';
            $response[] = array(
                'nombre' => $row['name_file'],
                'tipo_persona' => $row['type_person_type'],
                'tipo_cargo' => $row['type_file'],
                'departamento' => $row['departament_file'],
                'municipio' => $row['city_file'],
                'porcentaje' => $percent
            );

            //echo '<pre>';print_r($response);echo '</pre>';
        }

        for ($i=0; $i < count($response); $i++) { 
        
            if($response[$i]["porcentaje"] >= $porcentaje){ 
            //echo '<pre>';print_r($i);echo '</pre>';
             //echo '<pre>';print_r($response[$i]);echo '</pre>';
             $return[$contador] = $response[$i];
             $contador++;
             //return $response[$i];
            }
            
                
        }
        //echo '<pre>';print_r($return);echo '</pre>';
        //return;
        //capturar logs en base de datos por consulta.
        $log = array(
            "response_log"=>print_r($return, true),
            "name_porcentaje_log"=> $name ." | ". $porcentaje,
            "date_created_log"=>date("Y-m-d H:i:s")
            );
           
        Conexion::logRegister('logs', $log, null);

        //echo '<pre>';print_r($return);echo '</pre>';
        return $return;
    }

    //Petición get sin filtro
    static public function getData($table, $select){
        
        $selectArray =  explode(",", $select);
        //validar existencia de la tabla y de las columnas

        //echo '<pre>';print_r(Conexion::getColumnsData($table, $selectArray));echo '</pre>';
        ///return;

        if(empty(Conexion::getColumnsData($table, $selectArray))){
            return null;
        }

        $sql = "SELECT $select FROM $table";

        $stmt = Conexion::connect()->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    //Petición get CON FILTRO
    static public function getDataFilter($table, $select, $campo, $equal){

        
        $campoToArray = explode(",", $campo);
        $equalToArray = explode(",", $equal);
        $campoToText = "";

        if(count($campoToArray)>0){
            foreach($campoToArray as $key => $valor){
                if($key > 0){
                    $campoToText .= "AND " . $valor . " = :" . $valor . " ";
                }
            }
        }

        $sql = "SELECT $select FROM $table WHERE $campoToArray[0] = :$campoToArray[0] $campoToText";

        //echo '<pre>';print_r($sql);echo '</pre>';
        //return;
        
        $stmt = Conexion::connect()->prepare($sql);
        
        foreach($campoToArray as $key => $valor){

            $stmt->bindParam(":".$valor , $equalToArray[$key], PDO::PARAM_STR);

        }
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }
}
?>