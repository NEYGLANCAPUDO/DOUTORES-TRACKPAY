<?php
session_start();
date_default_timezone_set('America/Boa_Vista');
require_once __DIR__ . '/../model/income.php';
class incomeController {
    private function voltar($pagina, $mensagem = '') {
        if($mensagem != '') $_SESSION['msg'] = $mensagem;
        header('Location: '.$pagina);
        exit;
    }
    private function conferirFormulario() {
        if(($_SERVER['REQUEST_METHOD'] ?? '') != 'POST') $this->voltar('../view/usuario/gerenciar_usuario.php', 'Envie o formulário corretamente.');
    }
    private function usuarioLogado() {
        if(empty($_SESSION['id'])) $this->voltar('../view/login.php', 'Faça login primeiro.');
        return (int)$_SESSION['id'];
    }
    private function valor($texto) {
        $texto = preg_replace('/[^0-9,.]/', '', trim((string)$texto));
        if(str_contains($texto, ',')) $texto = str_replace(',', '.', str_replace('.', '', $texto));
        if(!preg_match('/^\d+(\.\d{1,2})?$/', $texto)) return null;
        $valor = (float)$texto;
        return $valor > 0 && $valor <= 9999999999.99 && is_finite($valor) ? $valor : null;
    }
    public function renda() {
        $this->conferirFormulario();
        $id = $this->usuarioLogado();
        $acao = (string)($_POST['acaoRenda'] ?? 'adicionar');
        $registro = (int)($_POST['id_renda'] ?? 0);
        if($acao == 'excluir') {
            if($registro <= 0 || !delete_income($id, $registro)) throw new InvalidArgumentException('Renda não encontrada.');
        } else {
            if(!in_array($acao, ['adicionar','editar'], true)) throw new InvalidArgumentException('Ação inválida.');
            $descricao = trim((string)($_POST['descricao'] ?? ''));
            $valor = $this->valor($_POST['valor'] ?? '');
            $data = (string)($_POST['data_recebimento'] ?? '');
            $dataValida = DateTimeImmutable::createFromFormat('!Y-m-d', $data);
            if($descricao == '' || strlen($descricao) > 100 || $valor === null || !$dataValida || $dataValida->format('Y-m-d') != $data || ($acao == 'editar' && $registro <= 0)) throw new InvalidArgumentException('Confira os dados da renda.');
            if(!save_income($id, $descricao, $valor, $data, $acao == 'editar' ? $registro : null)) throw new InvalidArgumentException('Não foi possível salvar a renda.');
        }
        $this->voltar('../view/usuario/gerenciar_usuario.php', 'Renda atualizada.');
    }
}
$acao = (string)($_POST['acao'] ?? $_GET['acao'] ?? '');
$controller = new incomeController();
if($acao != 'renda') {
    $_SESSION['msg'] = 'Ação inválida.';
    header('Location: ../view/usuario/gerenciar_usuario.php');
    exit;
}
try {
    $controller->renda();
} catch(Throwable $erro) {
    error_log('TrackPay renda: '.$erro->getMessage());
    $_SESSION['msg'] = $erro instanceof InvalidArgumentException ? $erro->getMessage() : 'Erro ao salvar. Confira a conexão com o banco.';
    header('Location: ../view/usuario/gerenciar_usuario.php');
    exit;
}
