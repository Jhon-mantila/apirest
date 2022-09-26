<?php
require_once "modelos/connection.php";
require_once "controlador/post.cotroller.php";

if(isset($_POST)){

    $response = new PostController();

    //registro de usuarios
    if(isset($_GET['register']) && $_GET['register'] == true){

        $suffix = $_GET['suffix'] ?? "client";

        $response->postRegister("clients",$_POST, $suffix);

    }else if(isset($_GET['login']) && $_GET['login'] == true){

        $suffix = $_GET['suffix'] ?? "client";

        $response->postLogin("clients",$_POST, $suffix);

    }
}
?>