<?php
session_start();
require_once(__DIR__ . "/../model/user.php");

class usuarioController {

    public function cadastrar() {
        if (
            empty($_POST['name'])
            || empty($_POST['email'])
            || empty($_POST['password'])
            || empty($_POST['pass_verify'])
        ) {
            $_SESSION['msg'] = "Preencha todos os campos devidamente porra.";
            header("Location: ../view/cadastrar.php");
            exit;
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['msg'] = "TEM QUE TER @";
            header("Location: ../view/cadastrar.php");
            exit;
        } elseif ($_POST["password"] != $_POST["pass_verify"]) {
            $_SESSION["msg"] = "as senhas precisam ser as mesmas porra";
            header("Location: ../view/cadastrar.php");
            exit;
        } elseif (strlen($_POST['password']) < 8 || strlen($_POST['pass_verify']) < 8) {
            $_SESSION['msg'] = 'A SENHA TEM QUE SER MAIOR QUE OITO PORRA';
            header("Location: ../view/cadastrar.php");
            exit;
        } elseif (check_email($_POST["email"])) {
            $_SESSION['msg'] = 'JA EXITE ESSE GMAIL LOKO, OS 2 NE';
            header("Location: ../view/cadastrar.php");
            exit;
        } else {
            $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
            create_user($_POST["name"], $_POST["email"], $password);
            $_SESSION['msg'] = 'AGORA DEU PORRA';
            header("Location: ../view/login.php");
            exit;
        }
    }

    public function logar() {
        if (empty($_POST['email']) || empty($_POST['password'])) {
            $_SESSION['msg'] = "Preencha todos os campos devidamente.";
            header("Location: ../view/login.php");
            exit;
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['msg'] = "TEM QUE TER @";
            header("Location: ../view/login.php");
            exit;
        } elseif (strlen($_POST['password']) < 8) {
            $_SESSION['msg'] = 'COLOCA SENHA MAIOR QUE 8';
            header("Location: ../view/login.php");
            exit;
        }

        $user = get_user_by_email($_POST["email"]);

        // Verifica se o usuário existe e se a senha confere
        if ($user && password_verify($_POST["password"], $user['senha'])) {
            $_SESSION['id'] = $user['id'];
            header("Location: ../view/usuario/index_usuario.php");
            exit;
        } else {
            $_SESSION['msg'] = "E-mail ou senha incorretos.";
            header("Location: ../view/login.php");
            exit;
        }
    }

    public function atualizar_usuario() {
        $id = $_SESSION['id'];
        $user = get_user_by_id($id);

        if (!empty($_POST['nome'])) {
            $name = $_POST['nome'];
        } else {
            $name = $user['nome'];
        }

        if (!empty($_POST['email'])) {
            $email = $_POST['email'];
        } else {
            $email = $user['email'];
        }

        update_user($id, $name, $email);
        header("Location: ../view/usuario/gerenciar_usuario.php");
        exit;
    }

    public function apagar_usuario() {
        $id = $_SESSION['id'];
        delete_user($id);
        session_destroy();
        header("Location: ../../index.php");
        exit;
    }

    public function logout() {
        $_SESSION = array();
        session_destroy();
        header("Location: ../../index.php");
        exit;
    }
}

$acao = $_REQUEST['acao'] ?? null;

if ($acao) {
    $instancia = new usuarioController();

    if ($acao == 'cadastrar') {
        $instancia->cadastrar();
    } elseif ($acao == 'logar') {
        $instancia->logar();
    } elseif ($acao == 'atualizar_usuario') {
        $instancia->atualizar_usuario();
    } elseif ($acao == 'apagar_usuario') {
        $instancia->apagar_usuario();
    } elseif ($acao == 'logout') {
        $instancia->logout();
    }
}