<?php
    session_start();
    require_once(__DIR__ . "/../model/user.php");
function update()
{
    $id = $_SESSION['id'];
    $user = get_user_by_id($id);
    if(!empty($_POST['nome'])) {
        $name = $_POST['nome'];
    }
    else {
        $name = $user['nome'];
    }
    if(!empty($_POST['email'])) {
        $email = $_POST['email'];
    }
    else {
        $email = $user['email'];
    }
    update_user($id, $name, $email);
    header("Location: ../view/usuario/gerenciar_usuario.php");
    exit;
}
update();
?>