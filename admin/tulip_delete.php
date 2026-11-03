<?php
require_once __DIR__ . '/auth_check.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    try {
        $stmt = $pdo->prepare("DELETE FROM `tulips` WHERE `id` = ?");
        $stmt->execute([$id]);
        header("Location: tulips.php?msg=deleted");
        exit;
    } catch (PDOException $e) {
        die("Gagal menghapus data: " . $e->getMessage());
    }
} else {
    header("Location: tulips.php");
    exit;
}
