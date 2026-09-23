<?php require_once(__DIR__ . "/../config/conexao.php");


function create_user($name, $email, $password)
{
    $conn = conn();
    $stmt = $conn->prepare("INSERT INTO user (nome, email, senha) VALUES  (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password);
    $stmt->execute();
    $stmt->close();
}

function check_email($email)
{
    $conn = conn();
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ? ");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result()->num_rows;
    if ($result > 0) {
        $stmt->close();
        return true;
    } else {
        $stmt->close();
        return false;
    }
}
function get_user_by_email($email)
{
    $conn = conn();
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ? ");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result() -> fetch_assoc();
    
    $stmt->close();
    return $result;
}
function get_user_by_id($id)
{
    $conn = conn();
    $stmt = $conn->prepare("SELECT * FROM user WHERE id_usuario = ? ");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result() -> fetch_assoc();
    
    $stmt->close();
    return $result;
}

?>