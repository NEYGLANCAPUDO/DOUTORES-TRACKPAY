<?php
    session_start();
    require_once(__DIR__ . "/../model/bill.php");
    
function cadastrarBill()
{
    $id = $_SESSION['id'];
    $descricao_bill = $_POST['descricao'];
    $valor_bill = $_POST['valor'];
    $importancia_bill = $_POST['importancia'];
    create_bill($descricao_bill, $valor_bill, $importancia_bill, $id);
    header("Location: ../view/usuario/index_usuario.php");
    exit;
}
cadastrarBill();
?>