<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
