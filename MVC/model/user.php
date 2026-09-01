<?php require_once("trackpay.php");

function create_user($name, $email, $password) {
    $conn = conn();
    $stmt = $conn -> prepare("INSERT INTO users (name, email, password) VALUES = (?, ?, ?)");
    $stmt -> bind_param("sss", $name, $email, $password);
    $stmt -> execute();
    $stmt -> close();
}

?>