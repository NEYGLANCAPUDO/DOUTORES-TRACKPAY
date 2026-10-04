<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>index_usurio</title>
    <link rel="stylesheet" href="style_usuario.css">
</head>
<body>
    <a class="botao-link" href="../../../index.php">Voltar ao index
    <a class="botao-link" href="gerenciar_usuario.php">Gerenciar usuario
</body>
