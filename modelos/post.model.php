<?php
class PostModel{

    //Petición Post para crear datos de forma dinamica
    static public function postData($table, $data, $suffix){
        //echo '<pre>';print_r($table);echo '</pre>';
        //echo '<pre>';print_r($data);echo '</pre>';
        //echo '<pre>';print_r($suffix);echo '</pre>';
        
        $column = "";
        $param = "";

        foreach($data as $key =>$value){
            $column .= $key.",";
            $param .=":".$key. ",";
        }
        $column = substr($column, 0, -1);
        $param = substr($param, 0, -1);
        //echo '<pre>';print_r($column);echo '</pre>';
        //echo '<pre>';print_r($param);echo '</pre>';
        //$id = "id_$suffix";
        //$uuid = "uuid()";
        $sql = "INSERT INTO $table ($column) VALUES($param)";
        $link = Conexion::connect();
        $stmt = $link->prepare($sql);
        

        foreach($data as $key => $value){
            $stmt->bindParam(":".$key, $data[$key], PDO::PARAM_STR);
        }
        
        
        if($stmt->execute()){
         
            $response = array(
                "lastId" => $link->lastInsertId(),
                "Results" => "El process was successfull"
            );
          
            return $response;
           
        }else{

            return null;
        }
        
    }
    static public function postRegister($table, $data){

    }
}
?>