<?php
require __DIR__ . '/auth.php';

$pageTitle = 'Clientes';
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'delete') {
        $stmt = $pdo->prepare('DELETE FROM clients WHERE id = :id');
        $stmt->execute(['id' => $_POST['id']]);
        header('Location: clients.php');
        exit;
    }

    $name = trim($_POST['name'] ?? '');
    $sortOrder = (int) ($_POST['sort_order'] ?? 0);
    $active = isset($_POST['active']) ? 1 : 0;

    if ($name === '') {
        $error = 'El nombre es obligatorio.';
    } else {
        try {
            $logoPath = handle_image_upload($_FILES['logo'] ?? [], 'clients');
        } catch (RuntimeException $e) {
            $error = $e->getMessage();
        }

        if (!$error) {
            if ($action === 'update' && !empty($_POST['id'])) {
                if ($logoPath) {
                    $stmt = $pdo->prepare('UPDATE clients SET name = :name, sort_order = :sort_order, active = :active, logo_path = :logo_path WHERE id = :id');
                    $stmt->execute(['name' => $name, 'sort_order' => $sortOrder, 'active' => $active, 'logo_path' => $logoPath, 'id' => $_POST['id']]);
                } else {
                    $stmt = $pdo->prepare('UPDATE clients SET name = :name, sort_order = :sort_order, active = :active WHERE id = :id');
                    $stmt->execute(['name' => $name, 'sort_order' => $sortOrder, 'active' => $active, 'id' => $_POST['id']]);
                }
            } else {
                $stmt = $pdo->prepare('INSERT INTO clients (name, sort_order, active, logo_path) VALUES (:name, :sort_order, :active, :logo_path)');
                $stmt->execute(['name' => $name, 'sort_order' => $sortOrder, 'active' => $active, 'logo_path' => $logoPath ?: 'assets/img/placeholder-logo.svg']);
            }
            header('Location: clients.php');
            exit;
        }
    }
}

$clients = $pdo->query('SELECT * FROM clients ORDER BY sort_order ASC, id ASC')->fetchAll();
$editItem = null;
if (!empty($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM clients WHERE id = :id');
    $stmt->execute(['id' => $_GET['edit']]);
    $editItem = $stmt->fetch() ?: null;
}

require __DIR__ . '/includes/admin-header.php';
?>
<div class="admin-grid">
    <div class="admin-card">
        <h2><?= $editItem ? 'Editar Cliente' : 'Nuevo Cliente' ?></h2>
        <?php if ($error): ?><div class="form-alert error"><?= e($error) ?></div><?php endif; ?>
        <form method="post" class="admin-form" enctype="multipart/form-data">
            <input type="hidden" name="action" value="<?= $editItem ? 'update' : 'create' ?>">
            <?php if ($editItem): ?><input type="hidden" name="id" value="<?= e((string)$editItem['id']) ?>"><?php endif; ?>

            <label for="name">Nombre</label>
            <input type="text" id="name" name="name" required value="<?= e($editItem['name'] ?? '') ?>">

            <label for="logo">Logo <?= $editItem ? '(dejar vacío para conservar el actual)' : '' ?></label>
            <input type="file" id="logo" name="logo" accept="image/jpeg,image/png,image/webp">
            <?php if ($editItem): ?><img src="../<?= e($editItem['logo_path']) ?>" alt="" class="admin-thumb"><?php endif; ?>

            <label for="sort_order">Orden</label>
            <input type="number" id="sort_order" name="sort_order" value="<?= e((string)($editItem['sort_order'] ?? 0)) ?>">

            <label class="checkbox-label">
                <input type="checkbox" name="active" <?= ($editItem['active'] ?? 1) ? 'checked' : '' ?>> Activo
            </label>

            <button type="submit" class="btn btn-accent"><?= $editItem ? 'Guardar Cambios' : 'Crear Cliente' ?></button>
            <?php if ($editItem): ?><a href="clients.php" class="btn-link">Cancelar</a><?php endif; ?>
        </form>
    </div>

    <div class="admin-card">
        <h2>Clientes Existentes</h2>
        <table class="admin-table">
            <thead><tr><th>Logo</th><th>Nombre</th><th>Activo</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($clients as $client): ?>
                <tr>
                    <td><img src="../<?= e($client['logo_path']) ?>" alt="" class="admin-thumb-sm"></td>
                    <td><?= e($client['name']) ?></td>
                    <td><?= $client['active'] ? 'Sí' : 'No' ?></td>
                    <td class="table-actions">
                        <a href="clients.php?edit=<?= (int) $client['id'] ?>">Editar</a>
                        <form method="post" onsubmit="return confirm('¿Eliminar este cliente?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= (int) $client['id'] ?>">
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
