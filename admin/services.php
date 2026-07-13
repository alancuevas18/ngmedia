<?php
require __DIR__ . '/auth.php';

$pageTitle = 'Servicios';
$error = null;
$iconOptions = ['megaphone', 'chart', 'handshake', 'signal', 'flag', 'building', 'badge', 'palette'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'delete') {
        $stmt = $pdo->prepare('DELETE FROM services WHERE id = :id');
        $stmt->execute(['id' => $_POST['id']]);
        header('Location: services.php');
        exit;
    }

    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $iconSlug = in_array($_POST['icon_slug'] ?? '', $iconOptions, true) ? $_POST['icon_slug'] : 'megaphone';
    $sortOrder = (int) ($_POST['sort_order'] ?? 0);
    $active = isset($_POST['active']) ? 1 : 0;

    if ($title === '') {
        $error = 'El título es obligatorio.';
    } else {
        if ($action === 'update' && !empty($_POST['id'])) {
            $stmt = $pdo->prepare(
                'UPDATE services SET title = :title, description = :description, icon_slug = :icon_slug, sort_order = :sort_order, active = :active WHERE id = :id'
            );
            $stmt->execute([
                'title' => $title, 'description' => $description, 'icon_slug' => $iconSlug,
                'sort_order' => $sortOrder, 'active' => $active, 'id' => $_POST['id'],
            ]);
        } else {
            $stmt = $pdo->prepare(
                'INSERT INTO services (title, description, icon_slug, sort_order, active) VALUES (:title, :description, :icon_slug, :sort_order, :active)'
            );
            $stmt->execute([
                'title' => $title, 'description' => $description, 'icon_slug' => $iconSlug,
                'sort_order' => $sortOrder, 'active' => $active,
            ]);
        }
        header('Location: services.php');
        exit;
    }
}

$services = $pdo->query('SELECT * FROM services ORDER BY sort_order ASC, id ASC')->fetchAll();
$editItem = null;
if (!empty($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM services WHERE id = :id');
    $stmt->execute(['id' => $_GET['edit']]);
    $editItem = $stmt->fetch() ?: null;
}

require __DIR__ . '/includes/admin-header.php';
?>
<div class="admin-grid">
    <div class="admin-card">
        <h2><?= $editItem ? 'Editar Servicio' : 'Nuevo Servicio' ?></h2>
        <?php if ($error): ?><div class="form-alert error"><?= e($error) ?></div><?php endif; ?>
        <form method="post" class="admin-form">
            <input type="hidden" name="action" value="<?= $editItem ? 'update' : 'create' ?>">
            <?php if ($editItem): ?><input type="hidden" name="id" value="<?= e((string)$editItem['id']) ?>"><?php endif; ?>
            <label for="title">Título</label>
            <input type="text" id="title" name="title" required value="<?= e($editItem['title'] ?? '') ?>">

            <label for="description">Descripción</label>
            <textarea id="description" name="description" rows="3"><?= e($editItem['description'] ?? '') ?></textarea>

            <label for="icon_slug">Ícono</label>
            <select id="icon_slug" name="icon_slug">
                <?php foreach ($iconOptions as $icon): ?>
                <option value="<?= e($icon) ?>" <?= ($editItem['icon_slug'] ?? '') === $icon ? 'selected' : '' ?>><?= e($icon) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="sort_order">Orden</label>
            <input type="number" id="sort_order" name="sort_order" value="<?= e((string)($editItem['sort_order'] ?? 0)) ?>">

            <label class="checkbox-label">
                <input type="checkbox" name="active" <?= ($editItem['active'] ?? 1) ? 'checked' : '' ?>> Activo
            </label>

            <button type="submit" class="btn btn-accent"><?= $editItem ? 'Guardar Cambios' : 'Crear Servicio' ?></button>
            <?php if ($editItem): ?><a href="services.php" class="btn-link">Cancelar</a><?php endif; ?>
        </form>
    </div>

    <div class="admin-card">
        <h2>Servicios Existentes</h2>
        <table class="admin-table">
            <thead><tr><th>Orden</th><th>Título</th><th>Activo</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($services as $service): ?>
                <tr>
                    <td><?= (int) $service['sort_order'] ?></td>
                    <td><?= e($service['title']) ?></td>
                    <td><?= $service['active'] ? 'Sí' : 'No' ?></td>
                    <td class="table-actions">
                        <a href="services.php?edit=<?= (int) $service['id'] ?>">Editar</a>
                        <form method="post" onsubmit="return confirm('¿Eliminar este servicio?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= (int) $service['id'] ?>">
                            <button type="submit" class="btn-link-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require __DIR__ . '/includes/admin-footer.php'; ?>
