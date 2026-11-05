<?php
require_once(__DIR__ . '/../config/conexao.php');

function check_category($id)
{
    $conn = conn();
    $stmt = $conn->prepare("SELECT id FROM category WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->num_rows > 0;
    $stmt->close();
    return $result;
}

function get_categories()
{
    $conn = conn();
    $stmt = $conn->prepare("SELECT id, nome AS name FROM category ORDER BY id");
    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $result;
}
?>
