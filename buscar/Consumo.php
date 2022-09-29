<?php 
class Consumo{

    private function getToken(){


        //$url = "https://pruebajessika.000webhostapp.com//clients?login=true&suffix=client";
        $url = $this->config['url'] . "//".$this->config['tabla_login']."?login=true&suffix=client";
        $curl = curl_init();

        //echo $url; 

        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => "email_client=".$this->config['body']['username']."&password_client=".$this->config['body']['password'],
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
        ),
        ));

        $response = curl_exec($curl);

        $result_decode = json_decode($response);
      
        //print_r($result_decode, true);
        $token = $result_decode->result[0]->token_client;

        curl_close($curl);

        return $token;
        //echo '<pre>';echo $response;
        //echo '<pre>';echo $token;

    }

    public function getConsumo($name, $porcentaje){

        $curl = curl_init();

        $name = trim($name);
        $name = str_replace(" ", "%20", $name);
        
        $url = $this->config['url']. "//files?select=".$this->config['get']['select']."&name=".$name."&porcentaje=".$porcentaje."&token=".$this->getToken()."&table=clients&suffix=client";
        //echo $name;
        
        ///return;
        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);

        $result_decode = json_decode($response);

        return $result_decode;
        //echo '<pre>'; echo $response;
        //echo '<pre>'; echo print_r($result_decode, true);

    }

    private $config = array (

        //"url" =>"http://apirest.com",
        "url" =>"https://pruebajessika.000webhostapp.com",
        "tabla_login"=>"clients",
        "tabla_get"=>"files",
        "body" => array (
            "username"=>"jessika@correo.com",
            "password"=>"1234"
        ),

        "get"=>array(
            "select"=>"name_file,type_person_type,type_file,departament_file,city_file",
            
        )

    );
}
//$obj = new Consumo();
//$obj->getToken();
//$obj->getConsumo("Jhon Edinson Mantilla", 60);
?>