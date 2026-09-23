<?php session_start();
require_once("../model/user.php");

if (empty($_POST['email']) || empty($_POST['password'])) {
    $_SESSION['msg'] = "Preencha todos os campos devidamente.";
    header("Location: ../view/login.php");
    exit;
} elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $_SESSION['msg'] = "TEM QUE TER @";
    header("Location: ../view/login.php");
    exit;
} elseif (strlen($_POST['password']) < 8) {
    $_SESSION['msg'] = 'COLOCA SENHA MAIOR QUE 8';
    header("Location: ../view/login.php");
    exit;
}

$user = get_user_by_email($_POST["email"]);

// Verifica se o usuário existe e se a senha confere
if ($user && password_verify($_POST["password"], $user['senha'])) {
    $_SESSION['id'] = $user['id_usuario'];
    header("Location: ../../index.php");
    exit;
} else {
    $_SESSION['msg'] = "E-mail ou senha incorretos.";
    header("Location: ../view/login.php");
    exit;
}
?>