<?php
session_start();
date_default_timezone_set('America/Boa_Vista');
require_once __DIR__ . '/../model/user.php';
class usuarioController {
    private function usuarioLogado() {
    if(empty($_SESSION['id'])) $this->voltar('../view/login.php', 'Faça login primeiro.');
    return (int)$_SESSION['id'];
    }
    private function voltar($pagina, $mensagem = '') {
        if($mensagem != '') $_SESSION['msg'] = $mensagem;
        header('Location: '.$pagina);
        exit;
    }
    private function conferirFormulario() {
        if(($_SERVER['REQUEST_METHOD'] ?? '') != 'POST') $this->voltar('../view/login.php', 'Envie o formulário corretamente.');
    }
    public function cadastrar() {
        $this->conferirFormulario();
        $nome = trim((string)($_POST['name'] ?? ''));
        $email = strtolower(trim((string)($_POST['email'] ?? '')));
        $senha = (string)($_POST['password'] ?? '');
        if($nome == '' || strlen($nome) > 100 || strlen($email) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL)) $this->voltar('../view/cadastrar.php', 'Nome ou e-mail inválido.');
        if(strlen($senha) < 8 || $senha != (string)($_POST['pass_verify'] ?? '')) $this->voltar('../view/cadastrar.php', 'A senha deve ter pelo menos 8 caracteres e ser igual à confirmação.');
        if(check_email($email)) $this->voltar('../view/cadastrar.php', 'Esse e-mail já está cadastrado.');
        create_user($nome, $email, password_hash($senha, PASSWORD_DEFAULT));
        $this->voltar('../view/login.php', 'Cadastro concluído! Faça login.');
    }
    public function atualizar_usuario() {
        $this->conferirFormulario();
        $id = $this->usuarioLogado();
        $nome = trim((string)($_POST['nome'] ?? ''));
        $email = strtolower(trim((string)($_POST['email'] ?? '')));
        if($nome == '' || strlen($nome) > 100 || strlen($email) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL)) $this->voltar('../view/usuario/gerenciar_usuario.php', 'Dados inválidos.');
        if($email != strtolower(get_user_by_id($id)['email']) && check_email($email)) $this->voltar('../view/usuario/gerenciar_usuario.php', 'Esse e-mail já está em uso.');
        update_user($id, $nome, $email);
        $this->voltar('../view/usuario/gerenciar_usuario.php', 'Dados atualizados.');
    }
    public function apagar_usuario() {
        $this->conferirFormulario();
        delete_user($this->usuarioLogado());
        $_SESSION = [];
        session_destroy();
        $this->voltar('../../index.php');
    }
    public function logar() {
        $this->conferirFormulario();
        $email = strtolower(trim((string)($_POST['email'] ?? '')));
        $senha = (string)($_POST['password'] ?? '');
        if(!filter_var($email, FILTER_VALIDATE_EMAIL) || $senha == '') $this->voltar('../view/login.php', 'Preencha e-mail e senha.');
        $usuario = get_user_by_email($email);
        if(!$usuario || !password_verify($senha, $usuario['senha'])) $this->voltar('../view/login.php', 'E-mail ou senha incorretos.');
        session_regenerate_id(true);
        $_SESSION['id'] = (int)$usuario['id'];
        $this->voltar('../view/usuario/index_usuario.php');
    }
    public function logout() {
        $_SESSION = [];
        session_destroy();
        $this->voltar('../../index.php');
    }
}
$acao = (string)($_POST['acao'] ?? $_GET['acao'] ?? '');
$controller = new usuarioController();
if(!in_array($acao, ['cadastrar','logar','logout','atualizar_usuario','apagar_usuario'], true)) {
    $_SESSION['msg'] = 'Ação inválida.';
    header('Location: ../view/login.php');
    exit;
}
try {
    $controller->$acao();
} catch(Throwable $erro) {
    error_log('TrackPay usuário: '.$erro->getMessage());
    $_SESSION['msg'] = 'Erro ao salvar. Confira a conexão com o banco.';
    header('Location: '.($acao == 'cadastrar' ? '../view/cadastrar.php' : '../view/login.php'));
    exit;
}
