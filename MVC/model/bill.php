<?php
require_once(__DIR__ . '/../config/conexao.php');

// Mecânica de cadastro de contas do projeto "TrackPay - site foda".
function new_bill($name, $category, $price, $due_date, $description, $importance, $user_id)
{
    $conn = conn();
    $created_at = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("INSERT INTO bill (name, id_category, price, due_date, description, importance, paid, created_at, user_id) VALUES (?, ?, ?, ?, ?, ?, 0, ?, ?)");
    $stmt->bind_param("sidisssi", $name, $category, $price, $due_date, $description, $importance, $created_at, $user_id);
    $stmt->execute();
    $stmt->close();
}

function get_bills()
{
    $conn = conn();
    $stmt = $conn->prepare("SELECT * FROM bill");
    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $result;
}

// Funções usadas pelo fluxo de contas original do DOUTORES-TRACKPAY.
function create_bill($descricao_bill, $valor_bill, $importancia_bill, $id)
{
    $conn = conn();
    $due_date = (int) date('d');
    $category = 0;
    $description = $descricao_bill;
    $stmt = $conn->prepare("INSERT INTO bill (name, id_category, price, due_date, description, importance, paid, user_id) VALUES (?, ?, ?, ?, ?, ?, 0, ?)");
    $stmt->bind_param("sidissi", $descricao_bill, $category, $valor_bill, $due_date, $description, $importancia_bill, $id);
    $stmt->execute();
    $stmt->close();
}

function delete_bill($id, $id_conta)
{
    $conn = conn();

    $stmt = $conn->prepare("DELETE FROM fixedbill WHERE idbill = ? AND idbill IN (SELECT id FROM bill WHERE user_id = ?)");
    $stmt->bind_param("ii", $id_conta, $id);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM variablebill WHERE idbill = ? AND idbill IN (SELECT id FROM bill WHERE user_id = ?)");
    $stmt->bind_param("ii", $id_conta, $id);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM bill WHERE user_id = ? AND id = ?");
    $stmt->bind_param("ii", $id, $id_conta);
    $stmt->execute();
    $stmt->close();
}
?>
