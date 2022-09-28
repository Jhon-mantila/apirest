<?php
require_once "modelos/get.model.php";
require_once "modelos/post.model.php";
//require_once "vendor/autoload.php";
//use Firebase\JWT\JWT;

class Conexion{
    
    //Información de la base de datos
    static public function infoDatabase(){

        $infoDB = array(
            'database' => 'bd_prueba',
            'user' => 'root',
            'pass' => ''
        );
        return $infoDB;
    }

    //Conexión base de datos
    static public function connect(){

        try{


            $link = new PDO(
                "mysql:host=localhost;dbname=". Conexion::infoDatabase()["database"], 
                Conexion::infoDatabase()["user"],
                Conexion::infoDatabase()["pass"]  
            );

            $link->exec("set names utf8");

        }catch(PDOException $e){

            die("Error: " . $e->getMessage());
        }

        return $link;

    }

    static public function getColumnsData($table, $columns){

        //traer el nombre de la base de datos
        $database = Conexion::infoDatabase()["database"];

         //traer todas las columnas de una tabla
        $validate = Conexion::connect()
        ->query("SELECT COLUMN_NAME AS item FROM information_schema.columns WHERE table_schema = '$database' AND table_name = '$table'")
        ->fetchAll(PDO::FETCH_OBJ);

         //Validamos si existe una tabla
        if(empty($validate)){
            return null;
        }else{
            //Ajuste a solicitud a colunas globales

            if($columns[0] == "*"){
                //quito la posución 0
                array_shift($columns);
            }
            //validar existencias de columnas
            $sum = 0;
            foreach($validate as $key => $value){
               $sum += in_array($value->item, $columns);
                
            }
            //echo '<pre>';print_r($sum);echo '</pre>';

            //count($columns);
            //echo '<pre>';print_r(count($columns));echo '</pre>';

            return $sum == count($columns) ? $validate : null;
        }

    }

    //Generar Token de Autenticación
    static public function jwt($id, $email){

        $time = time();

        $token = array(

            "iat"=> $time, //Tiempo en que inicia el token
            "exp"=> $time + (60*60*24), /// Tiempo de expiración del token (1 día)
            "data" => [
                "id"=>$id,
                "email"=>$email
            ]

        );

        //$jwt = JWT::encode($token, "lkjflsafhjeuwornxnqegmncx", 'HS256');

        //echo '<pre>';print_r($jwt);echo '</pre>';
        return $token;

    }

    //validar token de seguridad

    static public function tokenValidate($token,$table,  $suffix){
        //echo '<pre>';print_r($token);echo '</pre>'; 
        //echo '<pre>';print_r($table);echo '</pre>'; 
        //echo '<pre>';print_r($suffix);echo '</pre>'; 
      
        $user = GetModel::getDataFilter($table, "token_exp_".$suffix, "token_".$suffix, $token);
       
        //echo '<pre>';print_r($user);echo '</pre>';
       
        //return;
        if(!empty($user)){
            //validar si el token no ha expirado
            $time = time();
            if($time < $user[0]->{"token_exp_".$suffix} ){
               
                return "vigente_token";

            }else{
                
                return "expiro_token";
            }
        }else{

            return "no_existe_token";
        }

        return;
    }

    static public function logRegister($table, $response, $suffix){

        $insert = PostModel::postData($table, $response, $suffix);
    }
    //registrar errores de json en el arhivo plano
    static public function logJsonControlados($response){
        
        $cadena = file_get_contents($_SERVER['DOCUMENT_ROOT']."/php_error_log");
        //echo '<pre>';print_r(__DIR__."\php_error_log");echo '</pre>';
        $cadena .= "\r\n".implode(",", $response);
        file_put_contents($_SERVER['DOCUMENT_ROOT']."/php_error_log", $cadena."[".date("Y-m-d H:i:s")."] "."\n");
    }
}
?>