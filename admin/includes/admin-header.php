<?php
/** @var string $pageTitle */
$currentPage = basename($_SERVER['SCRIPT_NAME']);
$navItems = [
    'index.php'     => 'Dashboard',
    'services.php'  => 'Servicios',
    'portfolio.php' => 'Portafolio',
    'clients.php'   => 'Clientes',
    'messages.php'  => 'Mensajes',
    'settings.php'  => 'Ajustes del Sitio',
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($pageTitle ?? 'Admin') ?> — NG Media Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="admin-body">
<div class="admin-shell">
    <aside class="admin-sidebar">
        <img src="../assets/img/logo-white.svg" alt="NG Media" class="admin-logo">
        <nav>
            <?php foreach ($navItems as $href => $label): ?>
            <a href="<?= e($href) ?>" class="<?= $currentPage === $href ? 'is-active' : '' ?>"><?= e($label) ?></a>
            <?php endforeach; ?>
        </nav>
        <a href="logout.php" class="admin-logout">Cerrar Sesión</a>
    </aside>
    <main class="admin-content">
        <header class="admin-topbar">
            <h1><?= e($pageTitle ?? '') ?></h1>
            <span>Hola, <?= e($_SESSION['admin_username'] ?? '') ?></span>
        </header>
