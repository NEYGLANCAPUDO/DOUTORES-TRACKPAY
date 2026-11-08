<?php
session_start();
date_default_timezone_set('America/Boa_Vista');

if (!isset($_SESSION['id'])) {
    header('Location: ../login.php');
    exit;
}
require_once __DIR__ . '/../../model/user.php';
require_once __DIR__ . '/../../model/bill.php';

$user = get_user_by_id((int) $_SESSION['id']);
if (!$user) {
    $_SESSION = [];
    session_destroy();
    header('Location: ../login.php');
    exit;
}

$navigation = month_navigation();
$month = $navigation['current'];
$billsUsuario = get_bills((int)$_SESSION['id'], $month);
$dataHoje = date('d/m/Y');
foreach ($billsUsuario as &$bill) {
    $date = bill_date_for_month($bill, new DateTimeImmutable($month . '-01'));
    $bill['next_due_date'] = $date ? $date->format('Y-m-d') : $month . '-01';
}
unset($bill);
usort($billsUsuario, fn($a, $b) => strcmp($a['next_due_date'], $b['next_due_date']));
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Linha do Tempo</title>
    <link rel="stylesheet" href="style_usuario.css">
</head>
<body>
    <a href="index_usuario.php?mes=<?= urlencode($month) ?>">Voltar a página principal</a>
    <p>Essa é sua linha do tempo financeira:</p>
    <p>Hoje é dia <?= htmlspecialchars($dataHoje, ENT_QUOTES, 'UTF-8') ?></p>
    <nav aria-label="Navegação por mês">
        <a href="?mes=<?= urlencode($navigation['previous']) ?>">&#10094; Anterior</a>
        <strong><?= htmlspecialchars((new DateTimeImmutable($month . '-01'))->format('m/Y'), ENT_QUOTES, 'UTF-8') ?></strong>
        <a href="?mes=<?= urlencode($navigation['next']) ?>">Próximo &#10095;</a>
    </nav>
    <div class="all-timeline">
        <?php foreach ($billsUsuario as $bill): ?>
            <div class="event-timeline">
                <div class="date-timeline">
                    <p>
                        <?= date(
                            'd/m/Y',
                            strtotime($bill['next_due_date'])
                        ) ?>
                    </p>
                    <h3>
                        <?= htmlspecialchars(
                            $bill['name'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </h3>
                    <h4>
                        R$
                        <?= number_format(
                            (float) $bill['price'],
                            2,
                            ',',
                            '.'
                        ) ?>
                    </h4>
                </div>
                <div class="content-timeline">
                    <p>
                        <?= htmlspecialchars(
                            $bill['description'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>