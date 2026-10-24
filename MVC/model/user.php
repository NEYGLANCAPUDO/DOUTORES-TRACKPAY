<?php
require_once(__DIR__ . "/../config/conexao.php");

function create_user($name, $email, $password)
{
    $conn = conn();
    $stmt = $conn->prepare("INSERT INTO `user` (nome, email, senha) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password);
    $stmt->execute();
    $stmt->close();
}

function check_email($email)
{
    $conn = conn();
    $stmt = $conn->prepare("SELECT id FROM `user` WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result()->num_rows > 0;
    $stmt->close();
    return $result;
}

function get_user_by_email($email)
{
    $conn = conn();
    $stmt = $conn->prepare("SELECT * FROM `user` WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $result;
}

function get_user_by_id($id)
{
    $conn = conn();
    $stmt = $conn->prepare("SELECT * FROM `user` WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $result;
}

function update_user($id, $nome, $email)
{
    $conn = conn();
    $stmt = $conn->prepare("UPDATE `user` SET nome = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssi", $nome, $email, $id);
    $stmt->execute();
    $stmt->close();
}

function delete_user($id)
{
    $conn = conn();
    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("DELETE FROM fixedbill WHERE idbill IN (SELECT id FROM bill WHERE user_id = ?)");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM variablebill WHERE idbill IN (SELECT id FROM bill WHERE user_id = ?)");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM bill WHERE user_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM income WHERE idUSUARIO = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM `user` WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        $conn->commit();
    } catch (Throwable $e) {
        $conn->rollback();
        throw $e;
    }
}
?>
