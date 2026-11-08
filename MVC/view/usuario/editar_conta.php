<?php
session_start();
date_default_timezone_set('America/Boa_Vista');
if(!isset($_SESSION['id'])) {header('Location: ../login.php');exit;}
require_once __DIR__.'/../../model/user.php';
require_once __DIR__.'/../../model/bill.php';
require_once __DIR__.'/../../model/category.php';
require_once __DIR__.'/../../model/income.php';
$id = (int)$_SESSION['id'];
$user = get_user_by_id($id);
if(!$user) {session_destroy();header('Location: ../login.php');exit;}
$mes = (string)($_GET['mes'] ?? date('Y-m'));
if(!preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $mes)) $mes = date('Y-m');
$contaId = (int)($_GET['id'] ?? 0);
$conta = $contaId > 0 ? get_bill_by_id($id, $contaId) : null;
if(!$conta) {http_response_code(404);echo 'Conta não encontrada. <a href="index_usuario.php">Voltar</a>';exit;}
$categorias = get_categories();
$variavel = bill_is_variable($conta);
$data = (string)($variavel ? $conta['spent_date'] : $conta['fixed_date']);
$renda = income_total_for_month($id, $mes);
$porcentagem = $renda > 0 ? ((float)$conta['price'] / $renda) * 100 : null;
$pago = 0;
foreach(get_bills($id, $mes) as $item) if((int)$item['id'] == $contaId) $pago = (int)$item['paid'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar conta | TrackPay</title>
</head>
<body>
    <a href="index_usuario.php?mes=<?= urlencode($mes) ?>">Voltar</a>
    <h1>Editar conta</h1>
    <?php if(isset($_SESSION['msg'])): ?>
        <p><?= htmlspecialchars($_SESSION['msg'], ENT_QUOTES, 'UTF-8') ?></p>
        <?php unset($_SESSION['msg']); ?>
    <?php endif; ?>
    <form method="post" action="../../controller/billController.php?acao=editarBill">
        <input type="hidden" name="id_bill" value="<?= (int)$conta['id'] ?>">
        <input type="hidden" name="mes" value="<?= htmlspecialchars($mes, ENT_QUOTES, 'UTF-8') ?>">
        <p><label>Nome da conta: <input type="text" name="name" value="<?= htmlspecialchars($conta['name'], ENT_QUOTES, 'UTF-8') ?>" maxlength="100" required></label></p>
        <p><label>Valor: <input type="number" name="price" step="0.01" min="0.01" value="<?= htmlspecialchars((string)$conta['price'], ENT_QUOTES, 'UTF-8') ?>" required></label></p>
        <p>Porcentagem da renda do mês: <?= $porcentagem === null ? 'Cadastre uma renda para calcular' : number_format($porcentagem, 1, ',', '.').'%' ?></p>
        <p><label>Tipo da conta: <select name="type"><option value="fixed" <?= !$variavel ? 'selected' : '' ?>>Fixa</option><option value="variable" <?= $variavel ? 'selected' : '' ?>>Variável</option></select></label></p>
        <p><label>Data de vencimento (conta fixa): <input type="date" name="due_date" value="<?= htmlspecialchars($variavel ? date('Y-m-d') : $data, ENT_QUOTES, 'UTF-8') ?>"></label></p>
        <p><label>Data do gasto (conta variável): <input type="date" name="data_gasto" value="<?= htmlspecialchars($variavel ? $data : date('Y-m-d'), ENT_QUOTES, 'UTF-8') ?>"></label></p>
        <p><label>Categoria: <select name="category"><option value="">Sem categoria</option><?php foreach($categorias as $categoria): ?><option value="<?= (int)$categoria['id'] ?>" <?= (int)$conta['id_category'] === (int)$categoria['id'] ? 'selected' : '' ?>><?= htmlspecialchars($categoria['name'], ENT_QUOTES, 'UTF-8') ?></option><?php endforeach; ?></select></label></p>
        <p><label>Descrição: <textarea name="description" maxlength="100" minlength="10" required><?= htmlspecialchars($conta['description'], ENT_QUOTES, 'UTF-8') ?></textarea></label></p>
        <p><label>Importância: <select name="importance"><option value="1" <?= $conta['importance'] == '1' ? 'selected' : '' ?>>Baixa</option><option value="2" <?= $conta['importance'] == '2' ? 'selected' : '' ?>>Média</option><option value="3" <?= $conta['importance'] == '3' ? 'selected' : '' ?>>Alta</option></select></label></p>
        <button type="submit">Salvar alterações</button>
    </form>
    <h2>Pagamento</h2>
    <form method="post" action="../../controller/billController.php?acao=paidBill">
        <input type="hidden" name="id_bill" value="<?= (int)$conta['id'] ?>">
        <input type="hidden" name="mes" value="<?= htmlspecialchars($mes, ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" name="origem" value="editar">
        <label><input type="checkbox" name="paidBill" value="1" <?= $pago ? 'checked' : '' ?> onchange="this.form.submit()"> Conta paga neste mês</label>
    </form>
    <h2>Excluir conta</h2>
    <form method="post" action="../../controller/billController.php?acao=deletarBill" onsubmit="return confirm('Deseja realmente excluir essa conta?')">
        <input type="hidden" name="id_conta" value="<?= (int)$conta['id'] ?>">
        <input type="hidden" name="mes" value="<?= htmlspecialchars($mes, ENT_QUOTES, 'UTF-8') ?>">
        <button type="submit">Apagar conta</button>
    </form>
</body>
</html>
