
<?php
session_start();
date_default_timezone_set('America/Boa_Vista');

if (!isset($_SESSION['id'])) {
    header('Location: ../login.php');
    exit;
}

require_once(__DIR__ . '/../../model/user.php');
require_once(__DIR__ . '/../../model/bill.php');
require_once(__DIR__ . '/../../model/category.php');

$user = get_user_by_id((int) $_SESSION['id']);
$navigation = month_navigation();
$month = $navigation['current'];
$bills = get_bills((int)$_SESSION['id'], $month);
$categories = get_categories();

if (!$user) {
    $_SESSION = [];
    session_destroy();
    header('Location: ../login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>Organizador Financeiro</title>

    <link rel="stylesheet" href="menu.css">

    <title>Minhas contas | TrackPay</title>
    <link rel="stylesheet" href="../../../assets/fontawesome-free-7.3.1-web/css/all.min.css">
    <link rel="stylesheet" href="../../../assets/css/style.css">
    <link rel="stylesheet" href="../../../assets/css/index.css">

</head>

<body>


    <header>
        <div class="header">
            <div class="profile">
                <a href="gerenciar_usuario.php" aria-label="Gerenciar usuário">
                    <div class="profile-photo"><i class="fa-solid fa-user"></i></div>
                </a>
                <h1>Organizador Financeiro</h1>
            </div>
            <div class="top-bar">
                <a href="gerenciar_usuario.php" class="btn btn-new">Minha conta</a>
                <a href="../../controller/usuarioController.php?acao=logout" class="btn btn-red">Sair</a>
            </div>
        </div>
    </header>

    <main class="container">
        <div class="father">
            <h2>Olá, <?= htmlspecialchars($user['nome'], ENT_QUOTES, 'UTF-8'); ?>!</h2>
            <div class="graphic">
                <h2>Gráfico aqui</h2>
            </div>
            <hr>
            <div class="timeline">
                <a href="timeline_usuario.php?mes=<?= urlencode($month) ?>">Linha do tempo</a>
            </div>

            <div class="pill-tag">
                <button type="button" onclick="document.getElementById('newBill').showModal()">
                    Nova conta
                </button>
            </div>

            <nav aria-label="Navegar entre meses" style="display:flex;justify-content:center;align-items:center;gap:1rem;margin:1rem 0;">
                <a href="?mes=<?= urlencode($navigation['previous']) ?>" aria-label="Mês anterior">&#10094;</a>
                <strong><?= htmlspecialchars((new DateTimeImmutable($month . '-01'))->format('m/Y'), ENT_QUOTES, 'UTF-8') ?></strong>
                <a href="?mes=<?= urlencode($navigation['next']) ?>" aria-label="Próximo mês">&#10095;</a>
            </nav>
            <?php if(isset($_SESSION['msg'])): ?>
                <p role="status"><?= htmlspecialchars($_SESSION['msg'], ENT_QUOTES, 'UTF-8') ?></p>
                <?php unset($_SESSION['msg']); ?>
            <?php endif; ?>
            <div class="bills-grid">
                <?php if (isset($bills)): ?>
                    <?php foreach ($bills as $bill): ?>
                        <?php if ((int) $bill['user_id'] === (int) $user['id']): ?>
                            <div class="bill">
                                <h4><?= htmlspecialchars($bill['name'], ENT_QUOTES, 'UTF-8'); ?></h4>
                                <p>Valor: R$ <?= number_format((float) $bill['price'], 2, ',', '.'); ?></p>
                                <p>Importância:
                                    <?php if ((string) $bill['importance'] === '1') { echo 'Baixa'; }
                                    elseif ((string) $bill['importance'] === '2') { echo 'Média'; }
                                    else { echo 'Alta'; } ?>
                                </p>
                                <p><?= bill_is_variable($bill) ? 'Data do gasto:' : 'Próxima cobrança:'; ?>
                                    <?php $nextDate = bill_date_for_month($bill, new DateTimeImmutable($month . '-01')); ?>
                                    <?= $nextDate ? htmlspecialchars($nextDate->format('d/m/Y'), ENT_QUOTES, 'UTF-8') : '-'; ?>
                                </p>
                                <p><?= htmlspecialchars($bill['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                                <form method="POST" action="../../controller/billController.php?acao=paidBill">
                                    <input type="hidden" name="id_bill" value="<?= (int) $bill['id']; ?>">
                                    <input type="hidden" name="mes" value="<?= htmlspecialchars($month, ENT_QUOTES, 'UTF-8') ?>">
                                    <input type="checkbox" name="paidBill" value="1" <?= (int) $bill['paid'] === 1 ? 'checked' : ''; ?> onchange="this.form.submit()">
                                </form>
                                <a class="btn btn-new" href="editar_conta.php?id=<?= (int)$bill['id'] ?>&mes=<?= urlencode($month) ?>">Editar conta</a>
                                <form method="POST" action="../../controller/billController.php?acao=deletarBill">
                                    <input type="hidden" name="id_conta" value="<?= (int) $bill['id']; ?>">
                                    <input type="hidden" name="mes" value="<?= htmlspecialchars($month, ENT_QUOTES, 'UTF-8') ?>">
                                    <button type="submit" class="btn btn-red">Apagar conta</button>
                                </form>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <dialog class="new-bill" id="newBill" style="position:fixed;inset:0;margin:auto;max-height:calc(100vh - 32px);max-width:calc(100vw - 32px);overflow:auto;">
        <div class="new-bill-links">
            <button type="button" onclick="document.getElementById('newBill').close()" class="btn btn-back" aria-label="Voltar">
                <i class="fa-solid fa-arrow-left-long"></i>
            </button>
            <h3>Nova Conta</h3>
        </div>
        <form action="../../controller/billController.php?acao=cadastrarBill" method="post">
            <input type="hidden" name="mes" value="<?= htmlspecialchars($month, ENT_QUOTES, 'UTF-8') ?>">
            <div class="field">
                <label for="new-bill-name">Nome da conta</label>
                <input type="text" name="name" id="new-bill-name" placeholder="Ex.: Mensalidade" required>
            </div>
            <div class="field">
                <label for="type">Tipo de conta:</label>
                <select name="type" id="type">
                    <option value="fixed">Conta fixa</option>
                    <option value="variable">Conta variável</option>
                </select>
            </div>
            <div class="field">
                <label for="category">Categoria</label>
                <select name="category" id="category">
                    <option value="" selected>Selecione uma categoria:</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= (int) $category['id']; ?>">
                            <?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field">
                <label for="new-bill-price">Valor da conta</label>
                <input type="text" name="price" id="new-bill-price" placeholder="R$ 0,00" inputmode="numeric" required>
            </div>
            <div id="fixedFields">
                <label>Data de vencimento:</label>
                <input type="date" name="due_date">
            </div>
            <div id="variableFields" style="display: none;">
                <label>Data do gasto:</label>
                <input type="date" name="data_gasto">
            </div>
            <div class="field">
                <label for="description">Descrição</label>
                <textarea name="description" id="description" placeholder="Min. 10 caracteres" required></textarea>
            </div>
            <div class="field">
                <label for="importance">Importância da conta</label>
                <select name="importance" id="importance" required>
                    <option value="3" selected>Alta</option>
                    <option value="2">Média</option>
                    <option value="1">Baixa</option>
                </select>
            </div>

            <?php if (isset($_SESSION['msg-modal'])): ?>
                <div class="msg"><p><?= htmlspecialchars($_SESSION['msg-modal'], ENT_QUOTES, 'UTF-8'); ?></p></div>
                <script>document.addEventListener('DOMContentLoaded', function () { document.getElementById('newBill').showModal(); });</script>
                <?php unset($_SESSION['msg-modal']); ?>
            <?php endif; ?>
            <button class="btn btn-submit" type="submit">Enviar</button>
        </form>
    </dialog>

    <footer class="rodape">
        <a href="../politicadeuso.php" class="link-politica">POLÍTICA DE PRIVACIDADE - TERMOS DE USO</a>
        <a href="../sobrenos.php" class="link-politica">SOBRE NÓS</a>
    </footer>

    <script src="../../../assets/js/scrollIntooView.js"></script>
    <script>
        const typeSelect = document.getElementById('type');
        const fixedFields = document.getElementById('fixedFields');
        const variableFields = document.getElementById('variableFields');
        function mostrarData() {
            const fixa = typeSelect.value === 'fixed';
            fixedFields.style.display = fixa ? 'block' : 'none';
            variableFields.style.display = fixa ? 'none' : 'block';
            fixedFields.querySelector('input').disabled = !fixa;
            variableFields.querySelector('input').disabled = fixa;
        }
        typeSelect.addEventListener('change', mostrarData);
        mostrarData();
        const priceInput = document.getElementById('new-bill-price');
        priceInput.addEventListener('input', function() {
            const value = priceInput.value.replace(/\D/g, '');
            priceInput.value = value ? (Number(value) / 100).toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'}) : '';
        });
    </script>

</body>
</html>
