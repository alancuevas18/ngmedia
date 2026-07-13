<?php
require __DIR__ . '/auth.php';

$pageTitle = 'Dashboard';

$unreadCount = (int) $pdo->query('SELECT COUNT(*) FROM contact_messages WHERE read_at IS NULL')->fetchColumn();
$servicesCount = (int) $pdo->query('SELECT COUNT(*) FROM services')->fetchColumn();
$portfolioCount = (int) $pdo->query('SELECT COUNT(*) FROM portfolio_items')->fetchColumn();
$clientsCount = (int) $pdo->query('SELECT COUNT(*) FROM clients')->fetchColumn();

require __DIR__ . '/includes/admin-header.php';
?>
<div class="stat-grid">
    <div class="stat-card">
        <span class="stat-value"><?= $unreadCount ?></span>
        <span class="stat-label">Mensajes sin leer</span>
        <a href="messages.php">Ver mensajes →</a>
    </div>
    <div class="stat-card">
        <span class="stat-value"><?= $servicesCount ?></span>
        <span class="stat-label">Servicios activos</span>
        <a href="services.php">Gestionar →</a>
    </div>
    <div class="stat-card">
        <span class="stat-value"><?= $portfolioCount ?></span>
        <span class="stat-label">Proyectos en portafolio</span>
        <a href="portfolio.php">Gestionar →</a>
    </div>
    <div class="stat-card">
        <span class="stat-value"><?= $clientsCount ?></span>
        <span class="stat-label">Clientes listados</span>
        <a href="clients.php">Gestionar →</a>
    </div>
</div>
<p class="admin-hint">Desde aquí puedes editar todo el contenido del sitio público: servicios, portafolio, clientes, textos del home y ver los mensajes recibidos desde el formulario de contacto.</p>
<?php require __DIR__ . '/includes/admin-footer.php'; ?>
