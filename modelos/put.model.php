<?php
require_once "connection.php";

class PutModel{

    //Petición editar datos
    static public function putData($table, $data, $id, $nameId){
        echo '<pre>';print_r($id);echo '</pre>';
        echo '<pre>';print_r($nameId);echo '</pre>';
        echo '<pre>';print_r($data);echo '</pre>';
        echo '<pre>';print_r($table);echo '</pre>';
    }
}
?>