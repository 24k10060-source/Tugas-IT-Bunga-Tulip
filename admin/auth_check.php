<?php
require_once __DIR__ . '/../setup/connection.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header("Location: ../auth/login.php?msg=unauthorized");
    exit;
}
