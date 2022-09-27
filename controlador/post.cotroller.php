<?php 
require_once "modelos/get.model.php";
require_once "modelos/post.model.php";
require_once "modelos/put.model.php";
require_once "modelos/connection.php";
require_once "vendor/autoload.php";
use Firebase\JWT\JWT;

class PostController{

    static public function postData($table, $data, $suffix){

        $response = PostModel::postData($table, $data, $suffix);
        //echo '<pre>';print_r($response);echo '</pre>';
        $return = new PostController();
        $return->fncResponse($response, null, null );

    }

    //Peticion para registrar usuarios
    static public function postRegister($table,$data, $suffix){

        if(isset($data["password_".$suffix]) && $data["password_".$suffix] != null){
            
            $crypt = crypt($data["password_".$suffix], '$2a$07$usesomesillystringforsalt$');
            $data["password_".$suffix] = $crypt;
            
            //echo '<pre>';print_r($data["password_".$suffix]);echo '</pre>';
            //return;

            $response = PostModel::postData($table, $data,$suffix);
            $return = new PostController();
            $return->fncResponse($response, null, $suffix);
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
                ///echo '<pre>';print_r($jwt);echo '</pre>';

                
                //Actualizar base de datos
                $data = array(
                    "token_".$suffix => $jwt,
                    "token_exp_".$suffix => $token["exp"]
                );

                $update = PutModel::putData($table, $data, $response[0]->{"id_".$suffix}, "id_".$suffix);

                //echo '<pre>';print_r($update);echo '</pre>';

                if(isset($update["Results"]) && $update["Results"] == "The proccess was successful"){

                    $response[0]->{"token_".$suffix} = $jwt;
                    $response[0]->{"token_exp_".$suffix} = $token["exp"];

                    $return = new PostController();
                    $return->fncResponse($response, null, $suffix);
                }

            }else{
                $response = null;
                $return = new PostController();
                $return->fncResponse($response, "Wrong Password", $suffix);
            }

        }else{
            
            $response = null;
            $return = new PostController();
            $return->fncResponse($response, "Wrong Email", $suffix);

        }//email
    }

    public function fncResponse($response, $error, $suffix){
        
        if(!empty($response)){
           
            //Quitar contraseña de la respuesta y otro campos

            if(isset($response[0]->{"password_".$suffix}) && isset($response[0]->{"name_".$suffix})){
                unset($response[0]->{"password_".$suffix});
                unset($response[0]->{"name_".$suffix});
            }
            
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
                Conexion::logJsonControlados($json);
            }else{
                $json = array(
                    'status' => 404,
                    'result' => 'No se inserto registro',
                    'method' => 'post'
                );
                Conexion::logJsonControlados($json);
            }

        }

        echo json_encode($json, http_response_code($json["status"]));
    }
}
?>