<?php
require_once __DIR__ . '/../config/conexao.php';
function create_user(string $name, string $email, string $passwordHash): void
{
    $db = conn();
    $stmt = $db->prepare(
        'INSERT INTO `user` (`nome`, `email`, `senha`) VALUES (?, ?, ?)'
    );
    $stmt->bind_param('sss', $name, $email, $passwordHash);
    $stmt->execute();
    $stmt->close();
    $db->close();
}
function check_email(string $email): bool
{
    $db = conn();
    $stmt = $db->prepare('SELECT id FROM `user` WHERE email = ? LIMIT 1');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $existe = (bool) $resultado->fetch_assoc();
    $stmt->close();
    $db->close();
    return $existe;
}
function get_user_by_email(string $email): ?array
{
    $db = conn();
    $stmt = $db->prepare(
        'SELECT * FROM `user` WHERE email = ? LIMIT 1'
    );
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $user = $resultado->fetch_assoc();
    $stmt->close();
    $db->close();
    return $user ?: null;
}
function get_user_by_id(int $id): ?array
{
    $db = conn();
    $stmt = $db->prepare(
        'SELECT * FROM `user` WHERE id = ? LIMIT 1'
    );
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $user = $resultado->fetch_assoc();
    $stmt->close();
    $db->close();
    return $user ?: null;
}
function update_user($id, $nome, $email) {
    $db = conn();
    $stmt = $db->prepare("UPDATE user SET nome = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssi", $nome, $email, $id);
    $resultado = $stmt->execute();
    $stmt->close();
    $db->close();
    return $resultado;
}
function delete_user($id) {
    $db = conn();
    $db->begin_transaction();
    try {
        foreach(['bill_month_payment','fixedbill','variablebill','bill','income','user'] as $tabela) {
            if($tabela == 'bill_month_payment') $sql = "DELETE FROM `$tabela` WHERE bill_id IN (SELECT id FROM bill WHERE user_id = ?)";
            elseif($tabela == 'fixedbill' || $tabela == 'variablebill') $sql = "DELETE FROM `$tabela` WHERE idbill IN (SELECT id FROM bill WHERE user_id = ?)";
            elseif($tabela == 'income') $sql = "DELETE FROM `$tabela` WHERE idUSUARIO = ?";
            elseif($tabela == 'user') $sql = "DELETE FROM `$tabela` WHERE id = ?";
            else $sql = "DELETE FROM `$tabela` WHERE user_id = ?";

            $stmt = $db->prepare($sql);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->close();
            }
        $db->commit();
    } catch(Throwable $e) {
        $db->rollback();
        throw $e;
    } finally {
        $db->close();
    }
}
