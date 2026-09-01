<?php session_start();
require_once("../model/user.php");

if (empty($_POST['name']) 
    || empty($_POST['email']) 
    || empty($_POST['password']) 
    || empty($_POST['pass_verify'])
    ){
    $_SESSION['msg'] = "Preencha todos os campos devidamente porra.";
    header("Location: ../view/cadastrar.php");
} elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
    $_SESSION['msg'] = "TEM QUE TER @";
    header("Location: ../view/cadastrar.php");
} elseif ($_POST["password"] != $_POST["pass_verify"]){
    $_SESSION["msg"] = "as senhas precisam ser as mesmas porra";
    header("Location: ../view/cadastrar.php");
} elseif (strlen($_POST['password']) < 8 || strlen($_POST['pass_verify']) < 8){
    
}


?>