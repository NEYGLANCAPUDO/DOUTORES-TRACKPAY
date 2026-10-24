<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: ../login.php');
    exit;
}

require_once(__DIR__ . '/../../model/user.php');
require_once(__DIR__ . '/../../model/bill.php');
require_once(__DIR__ . '/../../model/category.php');

$user = get_user_by_id((int) $_SESSION['id']);
$bills = get_bills();
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
                <h2>Linha do tempo</h2>
            </div>

            <div class="pill-tag">
                <button type="button" onclick="document.getElementById('newBill').showModal()">
                    Nova conta
                </button>
            </div>

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
                                <p>Próxima cobrança:
                                    <?= str_pad((string) $bill['due_date'], 2, '0', STR_PAD_LEFT) . '/';
                                    if ((int) $bill['due_date'] < (int) date('d')) {
                                        echo (int) date('m') + 1;
                                    } else {
                                        echo date('m');
                                    }
                                ?></p>
                                <p><?= htmlspecialchars($bill['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                                <form method="POST" action="../../controller/billController.php?acaoBill=deletarBill">
                                    <input type="hidden" name="id_conta" value="<?= (int) $bill['id']; ?>">
                                    <button type="submit" class="btn btn-red">Apagar conta</button>
                                </form>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <dialog class="new-bill" id="newBill">
        <div class="new-bill-links">
            <button type="button" onclick="document.getElementById('newBill').close()" class="btn btn-back" aria-label="Voltar">
                <i class="fa-solid fa-arrow-left-long"></i>
            </button>
            <h3>Nova Conta</h3>
        </div>
        <form action="../../controller/new_bill.php" method="post">
            <div class="field">
                <label for="new-bill-name">Nome da conta</label>
                <input type="text" name="name" id="new-bill-name" placeholder="Ex.: Mensalidade" required>
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
            <div class="field">
                <label for="new-bill-date">Dia de vencimento</label>
                <input type="date" name="due_date" id="new-bill-date" required>
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
        const priceInput = document.getElementById('new-bill-price');
        priceInput.addEventListener('input', () => {
            const value = priceInput.value.replace(/\D/g, '');
            priceInput.value = value ? (Number(value) / 100).toLocaleString('pt-BR', {
                style: 'currency', currency: 'BRL'
            }) : '';
        });
    </script>
</body>
</html>
