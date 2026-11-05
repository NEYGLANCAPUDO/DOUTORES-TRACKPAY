<?php
    session_start();
    require_once(__DIR__ . "/../model/bill.php");

function deletarConta()
{
    $id = $_SESSION['id'];
    $id_conta = $_POST['id_conta'];
    delete_bill($id, $id_conta);
    header("Location: ../view/usuario/index_usuario.php");
    exit;
}
deletarConta();
?>
