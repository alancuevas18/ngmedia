<?php
require __DIR__ . '/auth.php';

$pageTitle = 'Ajustes del Sitio';

$fields = [
    'hero_title'        => ['label' => 'Título del Hero', 'type' => 'text'],
    'hero_subtitle'      => ['label' => 'Subtítulo del Hero', 'type' => 'textarea'],
    'about_text'         => ['label' => 'Texto Sobre Nosotros', 'type' => 'textarea'],
    'mission_text'       => ['label' => 'Misión', 'type' => 'textarea'],
    'vision_text'        => ['label' => 'Visión', 'type' => 'textarea'],
    'values_text'        => ['label' => 'Valores', 'type' => 'textarea'],
    'contact_phone'      => ['label' => 'Teléfono de Contacto', 'type' => 'text'],
    'contact_email'      => ['label' => 'Email de Contacto', 'type' => 'text'],
    'contact_address'    => ['label' => 'Dirección', 'type' => 'text'],
    'whatsapp_number'    => ['label' => 'Número de WhatsApp (solo dígitos, con código de país)', 'type' => 'text'],
    'facebook_url'       => ['label' => 'URL de Facebook', 'type' => 'text'],
    'instagram_url'      => ['label' => 'URL de Instagram', 'type' => 'text'],
    'linkedin_url'       => ['label' => 'URL de LinkedIn', 'type' => 'text'],
    'youtube_url'        => ['label' => 'URL de YouTube', 'type' => 'text'],
    'meta_title'         => ['label' => 'Meta Title (SEO)', 'type' => 'text'],
    'meta_description'   => ['label' => 'Meta Description (SEO)', 'type' => 'textarea'],
];

$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare(
        'INSERT INTO site_content (content_key, content_value) VALUES (:key, :value)
         ON DUPLICATE KEY UPDATE content_value = :value'
    );
    foreach (array_keys($fields) as $key) {
        $value = trim($_POST[$key] ?? '');
        $stmt->execute(['key' => $key, 'value' => $value]);
    }
    $success = true;
}

$current = [];
foreach (array_keys($fields) as $key) {
    $current[$key] = fetch_content($pdo, $key);
}

require __DIR__ . '/includes/admin-header.php';
?>
<div class="admin-card">
    <h2>Contenido General</h2>
    <?php if ($success): ?><div class="form-alert success">Cambios guardados correctamente.</div><?php endif; ?>
    <form method="post" class="admin-form">
        <?php foreach ($fields as $key => $field): ?>
            <label for="<?= e($key) ?>"><?= e($field['label']) ?></label>
            <?php if ($field['type'] === 'textarea'): ?>
                <textarea id="<?= e($key) ?>" name="<?= e($key) ?>" rows="3"><?= e($current[$key]) ?></textarea>
            <?php else: ?>
                <input type="text" id="<?= e($key) ?>" name="<?= e($key) ?>" value="<?= e($current[$key]) ?>">
            <?php endif; ?>
        <?php endforeach; ?>
        <button type="submit" class="btn btn-accent">Guardar Cambios</button>
    </form>
</div>
<?php require __DIR__ . '/includes/admin-footer.php'; ?>
