
<?php
require_once __DIR__ . '/../config/conexao.php';

function new_bill(string $name, ?int $category, float $price, string $date, string $description, string $importance, int $userId, string $type): void
{
    $db = conn();
    try {
        $db->begin_transaction();
        $dueDay = (int) substr($date, 8, 2);
        $stmt = $db->prepare('INSERT INTO bill (name, id_category, price, due_date, description, importance, paid, user_id) VALUES (?, ?, ?, ?, ?, ?, 0, ?)');
        $stmt->bind_param('sidissi', $name, $category, $price, $dueDay, $description, $importance, $userId);
        $stmt->execute();
        $billId = (int) $db->insert_id;
        $stmt->close();
        if ($type === 'variable') {
            $stmt = $db->prepare('INSERT INTO variablebill (data_gasto, idbill) VALUES (?, ?)');
        } else {
            $stmt = $db->prepare('INSERT INTO fixedbill (vencimento, idbill) VALUES (?, ?)');
        }
        $stmt->bind_param('si', $date, $billId);
        $stmt->execute();
        $stmt->close();
        $db->commit();
    } catch (Throwable $e) {
        $db->rollback();
        throw $e;
    } finally {
        $db->close();
    }
}

function create_bill(string $description, float $price, string $importance, int $userId): void
{
    new_bill($description, null, $price, date('Y-m-d'), $description, $importance, $userId, 'variable');
}

function get_bills(?int $userId = null, ?string $month = null): array
{
    $authenticatedId = (int) $userId;
    if ($authenticatedId <= 0) return [];
    $month = $month ?? date('Y-m');
    if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $month)) throw new InvalidArgumentException('Mês inválido: use YYYY-MM.');
    $selected = $month;
    $db = conn();
    try {
        $stmt = $db->prepare("SELECT b.*, c.nome AS category_name, f.vencimento AS fixed_date, v.data_gasto AS spent_date, COALESCE(mp.paid, CASE WHEN ? = DATE_FORMAT(CURRENT_DATE(), '%Y-%m') THEN b.paid ELSE 0 END) AS monthly_paid FROM bill b LEFT JOIN category c ON c.id = b.id_category LEFT JOIN fixedbill f ON f.idbill = b.id LEFT JOIN variablebill v ON v.idbill = b.id LEFT JOIN bill_month_payment mp ON mp.bill_id = b.id AND mp.month_ref = ? WHERE b.user_id = ? ORDER BY b.created_at DESC, b.id DESC");
        $stmt->bind_param('ssi', $selected, $selected, $authenticatedId);
        $stmt->execute();
        $bills = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    } finally {
        $db->close();
    }
    $monthDate = new DateTimeImmutable($month . '-01');
    foreach ($bills as &$bill) {
        if (!bill_date_for_month($bill, $monthDate) || (!bill_is_variable($bill) && !empty($bill['fixed_date']) && substr((string) $bill['fixed_date'], 0, 7) > $month)) {
            $bill['_hidden_in_month'] = true;
        }
        $bill['paid'] = (int) $bill['monthly_paid'];
    }
    unset($bill);
    return array_values(array_filter($bills, fn($bill) => empty($bill['_hidden_in_month'])));
}

function get_bills_by_month(int $userId, string $month): array
{
    return get_bills($userId, $month);
}

function set_bill_paid(int $userId, int $billId, bool $paid, ?string $month = null): void
{
    $month = $month ?? date('Y-m');
    if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $month)) throw new InvalidArgumentException('Mês inválido.');
    $db = conn();
    try {
        $db->begin_transaction();
        $stmt = $db->prepare('SELECT id FROM bill WHERE id = ? AND user_id = ? LIMIT 1');
        $stmt->bind_param('ii', $billId, $userId);
        $stmt->execute();
        $exists = (bool) $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if (!$exists) throw new RuntimeException('Conta não encontrada.');
        $value = $paid ? 1 : 0;
        $stmt = $db->prepare('INSERT INTO bill_month_payment (bill_id, month_ref, paid) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE paid = VALUES(paid)');
        $stmt->bind_param('isi', $billId, $month, $value);
        $stmt->execute();
        $stmt->close();
        if ($month === date('Y-m')) {
            $stmt = $db->prepare('UPDATE bill SET paid = ? WHERE id = ? AND user_id = ?');
            $stmt->bind_param('iii', $value, $billId, $userId);
            $stmt->execute();
            $stmt->close();
        }
        $db->commit();
    } catch (Throwable $e) {
        $db->rollback();
        throw $e;
    } finally {
        $db->close();
    }
}

function update_bill(int $userId, int $billId, string $name, ?int $category, float $price, string $date, string $description, string $importance, string $type): bool
{
    $db = conn();
    try {
        $db->begin_transaction();
        $stmt = $db->prepare('SELECT id FROM bill WHERE id = ? AND user_id = ? LIMIT 1 FOR UPDATE');
        $stmt->bind_param('ii', $billId, $userId);
        $stmt->execute();
        $exists = (bool) $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if (!$exists) {
            $db->rollback();
            return false;
        }
        $day = (int) substr($date, 8, 2);
        $stmt = $db->prepare('UPDATE bill SET name = ?, id_category = ?, price = ?, due_date = ?, description = ?, importance = ? WHERE id = ? AND user_id = ?');
        $stmt->bind_param('sidissii', $name, $category, $price, $day, $description, $importance, $billId, $userId);
        $stmt->execute();
        $stmt->close();
        $remove = $type === 'fixed' ? 'variablebill' : 'fixedbill';
        $keep = $type === 'fixed' ? 'fixedbill' : 'variablebill';
        $field = $type === 'fixed' ? 'vencimento' : 'data_gasto';
        $stmt = $db->prepare("DELETE FROM `$remove` WHERE idbill = ?");
        $stmt->bind_param('i', $billId);
        $stmt->execute();
        $stmt->close();
        $stmt = $db->prepare("INSERT INTO `$keep` (`$field`, idbill) VALUES (?, ?) ON DUPLICATE KEY UPDATE `$field` = VALUES(`$field`)");
        $stmt->bind_param('si', $date, $billId);
        $stmt->execute();
        $stmt->close();
        $db->commit();
        return true;
    } catch (Throwable $e) {
        $db->rollback();
        throw $e;
    } finally {
        $db->close();
    }
}

function delete_bill(int $userId, int $billId): void
{
    $db = conn();
    try {
        $db->begin_transaction();
        $stmt = $db->prepare('DELETE FROM bill_month_payment WHERE bill_id = ? AND bill_id IN (SELECT id FROM bill WHERE user_id = ?)');
        $stmt->bind_param('ii', $billId, $userId);
        $stmt->execute();
        $stmt->close();
        foreach (['fixedbill', 'variablebill'] as $table) {
            $stmt = $db->prepare("DELETE FROM `$table` WHERE idbill = ? AND idbill IN (SELECT id FROM bill WHERE user_id = ?)");
            $stmt->bind_param('ii', $billId, $userId);
            $stmt->execute();
            $stmt->close();
        }
        $stmt = $db->prepare('DELETE FROM bill WHERE id = ? AND user_id = ?');
        $stmt->bind_param('ii', $billId, $userId);
        $stmt->execute();
        $stmt->close();
        $db->commit();
    } catch (Throwable $e) {
        $db->rollback();
        throw $e;
    } finally {
        $db->close();
    }
}

function bill_is_variable(array $bill): bool
{
    return !empty($bill['spent_date']);
}

function bill_date_for_month(array $bill, DateTimeImmutable $month): ?DateTimeImmutable
{
    if (bill_is_variable($bill)) {
        $date = DateTimeImmutable::createFromFormat('!Y-m-d', (string) $bill['spent_date']);
        return $date && $date->format('Y-m') === $month->format('Y-m') ? $date : null;
    }
    $day = max(1, min(31, (int) ($bill['due_date'] ?? 1)));
    $lastDay = (int) $month->format('t');
    return $month->setDate((int) $month->format('Y'), (int) $month->format('m'), min($day, $lastDay));
}

function next_bill_date(array $bill, ?DateTimeImmutable $today = null): ?DateTimeImmutable
{
    if ($today === null && isset($_GET['mes']) && preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', (string) $_GET['mes'])) {
        return bill_date_for_month($bill, new DateTimeImmutable((string) $_GET['mes'] . '-01'));
    }
    $today = $today ?? new DateTimeImmutable('today');
    if (bill_is_variable($bill)) {
        return DateTimeImmutable::createFromFormat('!Y-m-d', (string) $bill['spent_date']) ?: null;
    }
    $month = $today->modify('first day of this month');
    $current = bill_date_for_month($bill, $month);
    if ($current !== null && $current >= $today) return $current;
    return bill_date_for_month($bill, $month->modify('first day of next month'));
}

function month_navigation(?string $month = null): array
{
    $month = $month ?? (isset($_GET['mes']) ? (string) $_GET['mes'] : date('Y-m'));
    if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $month)) $month = date('Y-m');
    $date = DateTimeImmutable::createFromFormat('!Y-m-d', $month . '-01');
    return ['current' => $date->format('Y-m'), 'previous' => $date->modify('-1 month')->format('Y-m'), 'next' => $date->modify('+1 month')->format('Y-m')];
}

function get_bill_by_id(int $userId, int $billId): ?array
{
    $db = conn();
    try {
        $stmt = $db->prepare('SELECT b.*, f.vencimento AS fixed_date, v.data_gasto AS spent_date FROM bill b LEFT JOIN fixedbill f ON f.idbill = b.id LEFT JOIN variablebill v ON v.idbill = b.id WHERE b.id = ? AND b.user_id = ? LIMIT 1');
        $stmt->bind_param('ii', $billId, $userId);
        $stmt->execute();
        $bill = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $bill ?: null;
    } finally {
        $db->close();
    }
}
