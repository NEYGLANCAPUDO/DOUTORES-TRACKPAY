<?php
session_start();
require_once(__DIR__ . '/../model/category.php');
require_once(__DIR__ . '/../model/bill.php');

if (!isset($_SESSION['id'])) {
    header('Location: ../view/login.php');
    exit;
}

$price = preg_replace('/[^\d,.]/', '', $_POST['price'] ?? '');
$price = str_replace('.', '', $price);
$price = str_replace(',', '.', $price);

if (
    empty($_POST['name']) ||
    empty($_POST['price']) ||
    !is_numeric($price) ||
    (float) $price <= 0 ||
    empty($_POST['due_date']) ||
    empty($_POST['description']) ||
    !in_array((string) ($_POST['importance'] ?? ''), ['1', '2', '3'], true)
) {
    $_SESSION['msg-modal'] = 'Preencha todos os campos devidamente';
    header('Location: ../view/usuario/index_usuario.php');
    exit;
} elseif (
    isset($_POST['category']) && $_POST['category'] !== '' &&
    !check_category((int) $_POST['category'])
) {
    $_SESSION['msg-modal'] = 'Selecione uma categoria válida';
    header('Location: ../view/usuario/index_usuario.php');
    exit;
} elseif (strlen(trim($_POST['description'])) < 10) {
    $_SESSION['msg-modal'] = 'A descrição deve conter ao menos 10 caracteres';
    header('Location: ../view/usuario/index_usuario.php');
    exit;
} else {
    $due_date = date('d', strtotime($_POST['due_date']));
    $category = (isset($_POST['category']) && $_POST['category'] !== '') ? (int) $_POST['category'] : 0;

    new_bill(
        trim($_POST['name']),
        $category,
        (float) $price,
        (int) $due_date,
        trim($_POST['description']),
        (string) $_POST['importance'],
        (int) $_SESSION['id']
    );

    $_SESSION['msg-modal'] = 'Conta criada com sucesso';
    header('Location: ../view/usuario/index_usuario.php');
    exit;
}
?>
