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

date_default_timezone_set('America/Boa_Vista');

$dataHoje = date('d/m/Y');
$hoje = new DateTime('today');

$billsUsuario = [];

foreach ($bills as $bill) {
    if ((int) $bill['user_id'] === (int) $user['id']) {
        $billsUsuario[] = $bill;
    }
}

foreach ($billsUsuario as &$bill) {
    $diaVencimento = (int) $bill['due_date'];

    $dataVencimento = new DateTime(
        $hoje->format('Y-m-01')
    );

    $ultimoDiaMes = (int) $dataVencimento->format('t');

    $dataVencimento->setDate(
        (int) $dataVencimento->format('Y'),
        (int) $dataVencimento->format('m'),
        min($diaVencimento, $ultimoDiaMes)
    );

    if ($dataVencimento < $hoje) {
        $dataVencimento->modify('first day of next month');

        $ultimoDiaMes = (int) $dataVencimento->format('t');

        $dataVencimento->setDate(
            (int) $dataVencimento->format('Y'),
            (int) $dataVencimento->format('m'),
            min($diaVencimento, $ultimoDiaMes)
        );
    }

    $bill['next_due_date'] = $dataVencimento->format('Y-m-d');
}

unset($bill);
usort($billsUsuario, function ($a, $b) {
    return $a['next_due_date'] <=> $b['next_due_date'];
});
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Linha do Tempo</title>
    <link rel="stylesheet" href="style_usuario.css">
</head>
<body>
    <a href="index_usuario.php">Voltar a página principal</a>
    <p>Essa é sua linha do tempo financeira:</p>
    <p>Hoje é dia <?= htmlspecialchars($dataHoje, ENT_QUOTES, 'UTF-8') ?></p>
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