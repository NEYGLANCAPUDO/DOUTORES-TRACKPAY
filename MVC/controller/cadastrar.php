<?php session_start();
require_once("../model/user.php");

if (
    empty($_POST['name'])
    || empty($_POST['email'])
    || empty($_POST['password'])
    || empty($_POST['pass_verify'])
) {
    $_SESSION['msg'] = "Preencha todos os campos devidamente porra.";
    header("Location: ../view/cadastrar.php");
    exit;
} elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $_SESSION['msg'] = "TEM QUE TER @";
    header("Location: ../view/cadastrar.php");
    exit;
} elseif ($_POST["password"] != $_POST["pass_verify"]) {
    $_SESSION["msg"] = "as senhas precisam ser as mesmas porra";
    header("Location: ../view/cadastrar.php");
    exit;
} elseif (strlen($_POST['password']) < 8 || strlen($_POST['pass_verify']) < 8) {
    $_SESSION['msg'] = 'A SENHA TEM QUE SER MAIOR QUE OITO PORRA';
    header("Location: ../view/cadastrar.php");
    exit;


} elseif (check_email($_POST["email"])) {
    $_SESSION['msg'] = 'JA EXITE ESSE GMAIL LOKO, OS 2 NE';
    header("Location: ../view/cadastrar.php");
    exit;
}else {
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    create_user($_POST["name"], $_POST["email"], $password);
     $_SESSION['msg'] = 'AGORA DEU PORRA';
    header("Location: ../view/login.php");
    exit;
}





?>