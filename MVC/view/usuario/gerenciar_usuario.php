<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id'])) {
    header("Location: ../../login.php");
    exit;
}

require_once(__DIR__ . "/../../config/auth.php");
require_once(__DIR__ . "/../../model/user.php");

$user = get_user_by_id($_SESSION['id']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>gerenciar_usuario</title>
    <link rel="stylesheet" href="style_usuario.css">
</head>
<body>
    <p> Olá <?= $user['nome']  ?>, seu email é:<?= $user['email'] ?>. </p>
    <form method="POST" action="../../controller/deleteUser.php">
        <button type="submit">Apagar sua conta</button>
    </form>
    <form method="POST" action="../../controller/updateUser.php">
        <label> Mudar seu nome </label>
        <input type="text" name="nome" value="<?= $user['nome'] ?>" required>
        <input type="text" name="email" value="<?= $user['email'] ?>" required>
        <button type=submit>Confirmar</button>
    </form>

    
    <a class="botao-link" href="index_usuario.php">Voltar para a sua página
</body>