<?php
require_once __DIR__ . '/../config/conexao.php';
function get_incomes(int $id, ?string $month = null): array {
    $db = conn();
    try {
        if ($month !== null) {
            if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $month)) throw new InvalidArgumentException('Mês inválido');
            $start = $month . '-01';
            $end = (new DateTimeImmutable($start))->modify('first day of next month')->format('Y-m-d');
            $stmt = $db->prepare('SELECT * FROM income WHERE idUSUARIO=? AND data_recebimento >= ? AND data_recebimento < ? ORDER BY data_recebimento DESC, id DESC');
            $stmt->bind_param('iss', $id, $start, $end);
        } else {
            $stmt = $db->prepare('SELECT * FROM income WHERE idUSUARIO=? ORDER BY data_recebimento DESC, id DESC');
            $stmt->bind_param('i', $id);
        }
        $stmt->execute(); $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC); $stmt->close(); return $result;
    } finally { $db->close(); }
}
function save_income(int $id, string $description, float $value, string $date, ?int $incomeId = null): bool {
    $db = conn();
    try {
        if ($incomeId !== null) {
            $s=$db->prepare('UPDATE income SET descricao=?, valor=?, data_recebimento=? WHERE id=? AND idUSUARIO=?');
            $s->bind_param('sdsii', $description, $value, $date, $incomeId, $id);
        } else {
            $s=$db->prepare('INSERT INTO income (descricao, valor, data_recebimento, idUSUARIO) VALUES (?,?,?,?)');
            $s->bind_param('sdsi', $description, $value, $date, $id);
        }
        $s->execute(); $affected=$s->affected_rows; $s->close(); return $affected > 0;
    } finally { $db->close(); }
}
function delete_income(int $id, int $incomeId): bool {
    $db = conn();
    try {
        $s=$db->prepare('DELETE FROM income WHERE id=? AND idUSUARIO=?');
        $s->bind_param('ii', $incomeId, $id); $s->execute(); $ok=$s->affected_rows > 0; $s->close(); return $ok;
    } finally { $db->close(); }
}
function income_total_for_month(int $id, string $month): float {
    $total=0.0; foreach(get_incomes($id, $month) as $row) $total+=(float)$row['valor']; return $total;
}
