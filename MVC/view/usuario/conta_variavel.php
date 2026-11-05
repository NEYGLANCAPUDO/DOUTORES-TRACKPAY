<?php
    session_start();
    if(!isset($_SESSION['id'])) {
        header("Location: ../login.php");
        exit;
    }
?>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>gerenciar_usuario</title>
    <link rel="stylesheet" href="style_usuario.css">
</head>
<body>
    <form method="POST" action="../../controller/billController.php?acaoBill=cadastrarBill">
        Insira conta: <br>
        Nome da conta: <input type="text" name="descricao" required> <br>
        Valor da conta: <input type="number" name="valor" required> <br>
        Importância da conta:  
            <select name="importancia" required> 
                <option value="3">Alta</option>
                <option value="2">Média</option>
                <option value="1">Baixa</option>
            </select> <br>
        <button type="submit">Confirmar </button> <br>
        <a class="botao-link" href="index_usuario.php">Voltar para a sua página
    </form>
</body>