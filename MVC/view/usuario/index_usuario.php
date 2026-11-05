
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Organizador Financeiro</title>

    <link rel="stylesheet" href="menu.css">
</head>

<body>

    <header class="menu-superior">

        <div class="logo">
            Organizador Financeiro
        </div>

        <nav>
            <a class="botao-link" href="../../../index.php">
                Voltar ao index
            </a>

            <a class="botao-link" href="gerenciar_usuario.php">
                Gerenciar usuário
            </a>
        </nav>

    </header>

</body>
</html>
