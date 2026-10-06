<?php require_once(__DIR__ . "/../config/conexao.php");

function create_bill($descricao_bill, $valor_bill, $importancia_bill, $id)
{
    $conn = conn();
    $stmt = $conn->prepare("INSERT INTO bill (descricao, valor, importancia, iduser) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siii", $descricao_bill, $valor_bill, $importancia_bill, $id);
    $stmt->execute();
    $stmt->close();
}
function delete_bill($id, $id_conta)
{
    $conn = conn();
    $stmt = $conn->prepare("DELETE FROM bill WHERE iduser = ? AND id_conta = ? ");
    $stmt->bind_param("ii", $id, $id_conta);
    $stmt->execute();
    $stmt->close();
}
?>