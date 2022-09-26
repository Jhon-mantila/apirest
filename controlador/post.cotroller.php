<?php 
require_once "modelos/get.model.php";
require_once "modelos/post.model.php";
require_once "modelos/connection.php";
require_once "vendor/autoload.php";
use Firebase\JWT\JWT;

class PostController{

    //Peticion para registrar usuarios
    static public function postRegister($table,$data, $suffix){

        if(isset($data["password_".$suffix]) && $data["password_".$suffix] != null){
            
            $crypt = crypt($data["password_".$suffix], '$2a$07$usesomesillystringforsalt$');
            $data["password_".$suffix] = $crypt;
            
            //echo '<pre>';print_r($data["password_".$suffix]);echo '</pre>';
            //return;

            $response = PostModel::postRegister($table, $data);
            $return = new PostController();
            $return->fncResponse($response, null);
        }
    }

    //Peticion para login
    static public function postLogin($table,$data, $suffix){

        //validar que el usuario exista en la base de datos
        $response = GetModel::getDataFilter($table, "*", "email_".$suffix, $data["email_".$suffix]);
        
        if(!empty($response)){
            //echo '<pre>';print_r($response);echo '</pre>';

            $crypt = crypt($data["password_".$suffix], '$2a$07$usesomesillystringforsalt$');
            
            if($response[0]->password_client == $crypt){
                
                //Empieza generación token
                $token = Conexion::jwt($response[0]->id_client, $response[0]->email_client);

                $jwt = JWT::encode($token, "lkjflsafhjeuwornxnqegmncx", 'HS256');
                //echo '<pre>';print_r($jwt);echo '</pre>';

                //Actualizar base de datos
                $data = array(
                    "token_".$suffix => $jwt,
                    "token_exp_".$suffix => $token["exp"]
                );

                //$update = 

            }else{
                $response = null;
                $return = new PostController();
                $return->fncResponse($response, "Wrong Password");
            }

        }else{
            
            $response = null;
            $return = new PostController();
            $return->fncResponse($response, "Wrong Email");

        }//email
    }

    public function fncResponse($response, $error){
        
        if(!empty($response)){
           
            $json = array(
                'status' => 200,
                'result' => $response
            );

        }else{
            if($error != null){
                $json = array(
                    'status' => 400,
                    'result' => $error
                );
            }else{
                $json = array(
                    'status' => 404,
                    'result' => 'No se inserto registro'
                );
            }

        }

        echo json_encode($json, http_response_code($json["status"]));
    }
}
?>