<?php
require __DIR__ . '/auth.php';

$pageTitle = 'Portafolio';
$error = null;
$categories = ['Político', 'Gubernamental', 'Corporativo'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'delete') {
        $stmt = $pdo->prepare('DELETE FROM portfolio_items WHERE id = :id');
        $stmt->execute(['id' => $_POST['id']]);
        header('Location: portfolio.php');
        exit;
    }

    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category = in_array($_POST['category'] ?? '', $categories, true) ? $_POST['category'] : 'Corporativo';
    $sortOrder = (int) ($_POST['sort_order'] ?? 0);
    $active = isset($_POST['active']) ? 1 : 0;

    if ($title === '') {
        $error = 'El título es obligatorio.';
    } else {
        try {
            $imagePath = handle_image_upload($_FILES['image'] ?? [], 'portfolio');
        } catch (RuntimeException $e) {
            $error = $e->getMessage();
        }

        if (!$error) {
            if ($action === 'update' && !empty($_POST['id'])) {
                if ($imagePath) {
                    $stmt = $pdo->prepare(
                        'UPDATE portfolio_items SET title = :title, description = :description, category = :category, sort_order = :sort_order, active = :active, image_path = :image_path WHERE id = :id'
                    );
                    $stmt->execute([
                        'title' => $title, 'description' => $description, 'category' => $category,
                        'sort_order' => $sortOrder, 'active' => $active, 'image_path' => $imagePath, 'id' => $_POST['id'],
                    ]);
                } else {
                    $stmt = $pdo->prepare(
                        'UPDATE portfolio_items SET title = :title, description = :description, category = :category, sort_order = :sort_order, active = :active WHERE id = :id'
                    );
                    $stmt->execute([
                        'title' => $title, 'description' => $description, 'category' => $category,
                        'sort_order' => $sortOrder, 'active' => $active, 'id' => $_POST['id'],
                    ]);
                }
            } else {
                $stmt = $pdo->prepare(
                    'INSERT INTO portfolio_items (title, description, category, sort_order, active, image_path) VALUES (:title, :description, :category, :sort_order, :active, :image_path)'
                );
                $stmt->execute([
                    'title' => $title, 'description' => $description, 'category' => $category,
                    'sort_order' => $sortOrder, 'active' => $active,
                    'image_path' => $imagePath ?: 'assets/img/placeholder-portfolio.svg',
                ]);
            }
            header('Location: portfolio.php');
            exit;
        }
    }
}

$items = $pdo->query('SELECT * FROM portfolio_items ORDER BY sort_order ASC, id DESC')->fetchAll();
$editItem = null;
if (!empty($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM portfolio_items WHERE id = :id');
    $stmt->execute(['id' => $_GET['edit']]);
    $editItem = $stmt->fetch() ?: null;
}

require __DIR__ . '/includes/admin-header.php';
?>
<div class="admin-grid">
    <div class="admin-card">
        <h2><?= $editItem ? 'Editar Proyecto' : 'Nuevo Proyecto' ?></h2>
        <?php if ($error): ?><div class="form-alert error"><?= e($error) ?></div><?php endif; ?>
        <form method="post" class="admin-form" enctype="multipart/form-data">
            <input type="hidden" name="action" value="<?= $editItem ? 'update' : 'create' ?>">
            <?php if ($editItem): ?><input type="hidden" name="id" value="<?= e((string)$editItem['id']) ?>"><?php endif; ?>

            <label for="title">Título</label>
            <input type="text" id="title" name="title" required value="<?= e($editItem['title'] ?? '') ?>">

            <label for="description">Descripción</label>
            <textarea id="description" name="description" rows="3"><?= e($editItem['description'] ?? '') ?></textarea>

            <label for="category">Categoría</label>
            <select id="category" name="category">
                <?php foreach ($categories as $cat): ?>
                <option value="<?= e($cat) ?>" <?= ($editItem['category'] ?? '') === $cat ? 'selected' : '' ?>><?= e($cat) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="image">Imagen <?= $editItem ? '(dejar vacío para conservar la actual)' : '' ?></label>
            <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp">
            <?php if ($editItem): ?><img src="../<?= e($editItem['image_path']) ?>" alt="" class="admin-thumb"><?php endif; ?>

            <label for="sort_order">Orden</label>
            <input type="number" id="sort_order" name="sort_order" value="<?= e((string)($editItem['sort_order'] ?? 0)) ?>">

            <label class="checkbox-label">
                <input type="checkbox" name="active" <?= ($editItem['active'] ?? 1) ? 'checked' : '' ?>> Activo
            </label>

            <button type="submit" class="btn btn-accent"><?= $editItem ? 'Guardar Cambios' : 'Crear Proyecto' ?></button>
            <?php if ($editItem): ?><a href="portfolio.php" class="btn-link">Cancelar</a><?php endif; ?>
        </form>
    </div>

    <div class="admin-card">
        <h2>Proyectos Existentes</h2>
        <table class="admin-table">
            <thead><tr><th>Imagen</th><th>Título</th><th>Categoría</th><th>Activo</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><img src="../<?= e($item['image_path']) ?>" alt="" class="admin-thumb-sm"></td>
                    <td><?= e($item['title']) ?></td>
                    <td><?= e($item['category']) ?></td>
                    <td><?= $item['active'] ? 'Sí' : 'No' ?></td>
                    <td class="table-actions">
                        <a href="portfolio.php?edit=<?= (int) $item['id'] ?>">Editar</a>
                        <form method="post" onsubmit="return confirm('¿Eliminar este proyecto?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
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
