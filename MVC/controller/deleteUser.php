<?php
    session_start();
    require_once(__DIR__ . "/../model/user.php");

function delete()
{
    $id = $_SESSION['id'];
    delete_user($id);
    session_destroy();
    header("Location: ../../index.php");
    exit;
}
delete();

?>


