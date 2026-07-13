<?php
require __DIR__ . '/auth.php';

$pageTitle = 'Mensajes de Contacto';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'mark_read' && !empty($_POST['id'])) {
        $stmt = $pdo->prepare('UPDATE contact_messages SET read_at = NOW() WHERE id = :id');
        $stmt->execute(['id' => $_POST['id']]);
    } elseif ($action === 'delete' && !empty($_POST['id'])) {
        $stmt = $pdo->prepare('DELETE FROM contact_messages WHERE id = :id');
        $stmt->execute(['id' => $_POST['id']]);
    }
    header('Location: messages.php');
    exit;
}

$messages = $pdo->query('SELECT * FROM contact_messages ORDER BY created_at DESC')->fetchAll();

require __DIR__ . '/includes/admin-header.php';
?>
<div class="admin-card">
    <h2>Mensajes Recibidos</h2>
    <?php if (!$messages): ?>
        <p>Aún no se han recibido mensajes.</p>
    <?php else: ?>
    <table class="admin-table">
        <thead><tr><th>Fecha</th><th>Nombre</th><th>Email</th><th>Teléfono</th><th>Mensaje</th><th>Estado</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($messages as $msg): ?>
            <tr class="<?= $msg['read_at'] ? '' : 'row-unread' ?>">
                <td><?= e(date('d/m/Y H:i', strtotime($msg['created_at']))) ?></td>
                <td><?= e($msg['name']) ?></td>
                <td><a href="mailto:<?= e($msg['email']) ?>"><?= e($msg['email']) ?></a></td>
                <td><?= e($msg['phone'] ?? '—') ?></td>
                <td class="message-cell"><?= nl2br(e($msg['message'])) ?></td>
                <td><?= $msg['read_at'] ? 'Leído' : 'Nuevo' ?></td>
                <td class="table-actions">
                    <?php if (!$msg['read_at']): ?>
                    <form method="post">
                        <input type="hidden" name="action" value="mark_read">
                        <input type="hidden" name="id" value="<?= (int) $msg['id'] ?>">
                        <button type="submit" class="btn-link">Marcar leído</button>
                    </form>
                    <?php endif; ?>
                    <form method="post" onsubmit="return confirm('¿Eliminar este mensaje?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= (int) $msg['id'] ?>">
                        <button type="submit" class="btn-link-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/includes/admin-footer.php'; ?>
