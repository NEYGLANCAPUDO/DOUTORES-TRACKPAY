<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
    date_default_timezone_set('America/Boa_Vista');
}

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

require_once(__DIR__ . "/../../model/user.php");
require_once(__DIR__ . "/../../model/income.php");

$_SESSION['token'] ??= bin2hex(random_bytes(32));
$user = get_user_by_id($_SESSION['id']);
$incomes = get_incomes((int)$_SESSION['id']);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Gerenciar usuário</title>

    <link rel="stylesheet" href="gerenciar.css">
</head>

<body>

    <div class="gerenciar-container">
        <?php if (isset($_SESSION['msg'])): ?>
            <p><?= htmlspecialchars($_SESSION['msg'], ENT_QUOTES, 'UTF-8'); ?></p>
            <?php unset($_SESSION['msg']); ?>
        <?php endif; ?>

        <h1>Gerenciar usuário</h1>

        <p>
            Olá <strong><?= htmlspecialchars($user['nome'], ENT_QUOTES, 'UTF-8') ?></strong>,
            seu email é: <?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?>.
        </p>

        <form method="POST" action="../../controller/usuarioController.php?acao=apagar_usuario">
            <input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION['token'], ENT_QUOTES, 'UTF-8') ?>">
            <button type="submit" class="btn-apagar">
                Apagar sua conta
            </button>
        </form>

        <form method="POST" action="../../controller/usuarioController.php?acao=atualizar_usuario">
            <input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION['token'], ENT_QUOTES, 'UTF-8') ?>">

            <label>Mudar seus dados</label>

            <input type="text" name="nome"
                value="<?= htmlspecialchars($user['nome'], ENT_QUOTES, 'UTF-8') ?>" required>

            <input type="email" name="email"
                value="<?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?>" required>

            <button type="submit">Confirmar</button>

        </form>

        <section>
            <h2>Minha renda</h2>
            <form method="post" action="../../controller/incomeController.php?acao=renda">
            <input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION['token'], ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="acaoRenda" value="adicionar">
                <label>Descrição <input type="text" name="descricao" maxlength="100" required></label>
                <label>Valor (R$) <input type="number" name="valor" min="0.01" step="0.01" required></label>
                <label>Data de recebimento <input type="date" name="data_recebimento" value="<?= date('Y-m-d') ?>" required></label>
                <button type="submit">Adicionar renda</button>
            </form>
            <?php foreach ($incomes as $income): ?>
                <details>
                    <summary><?= htmlspecialchars($income['descricao'], ENT_QUOTES, 'UTF-8') ?> — R$ <?= number_format((float)$income['valor'], 2, ',', '.') ?> — <?= date('d/m/Y', strtotime($income['data_recebimento'])) ?></summary>
                    <form method="post" action="../../controller/incomeController.php?acao=renda">
            <input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION['token'], ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="acaoRenda" value="editar">
                        <input type="hidden" name="id_renda" value="<?= (int)$income['id'] ?>">
                        <label>Descrição <input type="text" name="descricao" maxlength="100" value="<?= htmlspecialchars($income['descricao'], ENT_QUOTES, 'UTF-8') ?>" required></label>
                        <label>Valor <input type="number" name="valor" min="0.01" step="0.01" value="<?= htmlspecialchars((string)$income['valor'], ENT_QUOTES, 'UTF-8') ?>" required></label>
                        <label>Data <input type="date" name="data_recebimento" value="<?= htmlspecialchars($income['data_recebimento'], ENT_QUOTES, 'UTF-8') ?>" required></label>
                        <button type="submit">Salvar renda</button>
                    </form>
                    <form method="post" action="../../controller/incomeController.php?acao=renda">
            <input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION['token'], ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="acaoRenda" value="excluir">
                        <input type="hidden" name="id_renda" value="<?= (int)$income['id'] ?>">
                        <button type="submit">Excluir renda</button>
                    </form>
                </details>
            <?php endforeach; ?>
        </section>

        <a class="botao-link" href="index_usuario.php">
            Voltar para a sua página
        </a>

    </div>

</body>
</html>
