<?php
require_once __DIR__ . '/../config/conexao.php';
function check_category(int $id): bool
{
    $db = conn();
    try {
        $stmt = $db->prepare('SELECT id FROM category WHERE id = ? LIMIT 1');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $exists = (bool) $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $exists;
    } finally {
        $db->close();
    }
}
function get_categories(): array
{
    $db = conn();
    try {
        $result = $db->query('SELECT id, nome AS name FROM category WHERE id > 0 ORDER BY nome');
        $categories = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();
        return $categories;
    } finally {
        $db->close();
    }
}
