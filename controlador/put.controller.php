<?php
require_once "modelos/put.model.php";

class PutController{
        //Peticion put 
        
        static public function putData($table,$data, $id, $nameId){

    
                $response = PutModel::putData($table, $data, $id, $nameId);

                //echo '<pre>';print_r($response);echo '</pre>';
                //return;
                $return = new PutController();
                $return->fncResponse($response);
            
        }

        public function fncResponse($response){
        
            if(!empty($response)){
               
                $json = array(
                    'status' => 200,
                    'result' => $response
                );
    
            }else{
                
                $json = array(
                    'status' => 404,
                    'result' => 'No se encontraron resultados',
                    'method' => 'put'
                );
            }
    
            echo json_encode($json, http_response_code($json["status"]));
        }
}
?>