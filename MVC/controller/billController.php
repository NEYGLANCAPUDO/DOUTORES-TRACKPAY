
<?php
session_start();
date_default_timezone_set('America/Boa_Vista');
require_once __DIR__ . '/../model/bill.php';
require_once __DIR__ . '/../model/category.php';

class billController {
    private function voltar($pagina, $mensagem = '', $modal = false) {
        if($mensagem != '') $_SESSION[$modal ? 'msg-modal' : 'msg'] = $mensagem;
        header('Location: '.$pagina);
        exit;
    }

    private function conferirFormulario() {
        if(($_SERVER['REQUEST_METHOD'] ?? '') != 'POST') $this->voltar('../view/usuario/index_usuario.php', 'Envie o formulário corretamente.');
    }

    private function usuarioLogado() {
        if(empty($_SESSION['id'])) $this->voltar('../view/login.php', 'Faça login primeiro.');
        return (int)$_SESSION['id'];
    }

    private function mes() {
        $mes = (string)($_POST['mes'] ?? date('Y-m'));
        return preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $mes) ? $mes : date('Y-m');
    }

    private function paginaMes() {
        return '../view/usuario/index_usuario.php?mes='.$this->mes();
    }

    private function valor($texto) {
        $texto = preg_replace('/[^0-9,.]/', '', trim((string)$texto));
        if(str_contains($texto, ',')) $texto = str_replace(',', '.', str_replace('.', '', $texto));
        if(!preg_match('/^\d+(\.\d{1,2})?$/', $texto)) return null;
        $valor = (float)$texto;
        return $valor > 0 && $valor <= 9999999999.99 && is_finite($valor) ? $valor : null;
    }

    private function dadosConta() {
        $nome = trim((string)($_POST['name'] ?? ''));
        $descricao = trim((string)($_POST['description'] ?? ''));
        $tipo = (string)($_POST['type'] ?? 'fixed');
        $importancia = (string)($_POST['importance'] ?? '3');
        $valor = $this->valor($_POST['price'] ?? '');
        $categoria = trim((string)($_POST['category'] ?? ''));
        $categoria = $categoria === '' ? null : filter_var($categoria, FILTER_VALIDATE_INT, ['options'=>['min_range'=>1]]);
        $data = (string)($_POST[$tipo == 'variable' ? 'data_gasto' : 'due_date'] ?? '');
        $dataValida = DateTimeImmutable::createFromFormat('!Y-m-d', $data);
        if($nome == '' || strlen($nome) > 100 || strlen($descricao) < 10 || strlen($descricao) > 100 || $valor === null || !in_array($tipo, ['fixed','variable'], true) || !in_array($importancia, ['1','2','3'], true) || $categoria === false || !$dataValida || $dataValida->format('Y-m-d') != $data) throw new InvalidArgumentException('Confira nome, valor, tipo, data e descrição da conta (mínimo 10 caracteres).');
        if($categoria !== null && !check_category((int)$categoria)) throw new InvalidArgumentException('Categoria inválida.');
        return [$nome, $categoria, $valor, $data, $descricao, $importancia, $tipo];
    }

    public function cadastrarBill() {
        $this->conferirFormulario();
        $id = $this->usuarioLogado();
        if(isset($_POST['descricao']) && !isset($_POST['name'])) {
            $nome = trim((string)$_POST['descricao']);
            $valor = $this->valor($_POST['valor'] ?? '');
            $importancia = (string)($_POST['importancia'] ?? '3');
            if($nome == '' || strlen($nome) > 100 || $valor === null || !in_array($importancia, ['1','2','3'], true)) throw new InvalidArgumentException('Dados de conta inválidos.');
            create_bill($nome, $valor, $importancia, $id);
        } else {
            [$nome, $categoria, $valor, $data, $descricao, $importancia, $tipo] = $this->dadosConta();
            new_bill($nome, $categoria, $valor, $data, $descricao, $importancia, $id, $tipo);
        }
        $this->voltar($this->paginaMes(), 'Conta cadastrada.');
    }

    public function editarBill() {
        $this->conferirFormulario();
        $id = $this->usuarioLogado();
        $conta = (int)($_POST['id_bill'] ?? $_POST['id_conta'] ?? 0);
        if($conta <= 0 || !get_bill_by_id($id, $conta)) throw new InvalidArgumentException('Conta não encontrada.');
        [$nome, $categoria, $valor, $data, $descricao, $importancia, $tipo] = $this->dadosConta();
        update_bill($id, $conta, $nome, $categoria, $valor, $data, $descricao, $importancia, $tipo);
        $this->voltar($this->paginaMes(), 'Conta atualizada.');
    }

    public function deletarBill() {
        $this->conferirFormulario();
        $id = $this->usuarioLogado();
        $conta = (int)($_POST['id_conta'] ?? $_POST['id_bill'] ?? 0);
        if($conta <= 0 || !get_bill_by_id($id, $conta)) throw new InvalidArgumentException('Conta não encontrada.');
        delete_bill($id, $conta);
        $this->voltar($this->paginaMes(), 'Conta excluída.');
    }

    public function paidBill() {
        $this->conferirFormulario();
        $id = $this->usuarioLogado();
        $conta = (int)($_POST['id_bill'] ?? 0);
        if($conta <= 0) throw new InvalidArgumentException('Conta inválida.');
        set_bill_paid($id, $conta, ($_POST['paidBill'] ?? '') == '1', $this->mes());
        if(($_POST['origem'] ?? '') == 'editar') $this->voltar('../view/usuario/editar_conta.php?id='.$conta.'&mes='.$this->mes());
        $this->voltar($this->paginaMes());
    }
}

$acao = (string)($_POST['acao'] ?? $_GET['acao'] ?? '');
$controller = new billController();

if(!in_array($acao, ['cadastrarBill','editarBill','deletarBill','paidBill'], true)) {
    $_SESSION['msg'] = 'Ação inválida.';
    header('Location: ../view/usuario/index_usuario.php');
    exit;
}

try {
    $controller->$acao();
} catch(Throwable $erro) {
    error_log('TrackPay conta: '.$erro->getMessage());
    $mensagem = $erro instanceof InvalidArgumentException ? $erro->getMessage() : 'Erro ao salvar. Confira a conexão com o banco.';
    $_SESSION[$acao == 'cadastrarBill' ? 'msg-modal' : 'msg'] = $mensagem;
    $mes = preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', (string)($_POST['mes'] ?? '')) ? $_POST['mes'] : date('Y-m');
    $pagina = $acao == 'editarBill' ? '../view/usuario/editar_conta.php?id='.(int)($_POST['id_bill'] ?? $_POST['id_conta'] ?? 0).'&mes='.$mes : '../view/usuario/index_usuario.php?mes='.$mes;
    header('Location: '.$pagina);
    exit;
}
