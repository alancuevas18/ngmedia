<?php
/**
 * Helpers compartidos por el sitio público y el panel admin.
 * Requiere que config/database.php ya haya sido incluido ($pdo disponible).
 */

function fetch_content(PDO $pdo, string $key, string $default = ''): string
{
    static $cache = null;
    if ($cache === null) {
        $cache = [];
        $stmt = $pdo->query('SELECT content_key, content_value FROM site_content');
        foreach ($stmt->fetchAll() as $row) {
            $cache[$row['content_key']] = $row['content_value'];
        }
    }
    return $cache[$key] ?? $default;
}

function fetch_services(PDO $pdo, bool $onlyActive = true): array
{
    $sql = 'SELECT * FROM services';
    if ($onlyActive) {
        $sql .= ' WHERE active = 1';
    }
    $sql .= ' ORDER BY sort_order ASC, id ASC';
    return $pdo->query($sql)->fetchAll();
}

function fetch_portfolio(PDO $pdo, bool $onlyActive = true): array
{
    $sql = 'SELECT * FROM portfolio_items';
    if ($onlyActive) {
        $sql .= ' WHERE active = 1';
    }
    $sql .= ' ORDER BY sort_order ASC, id DESC';
    return $pdo->query($sql)->fetchAll();
}

function fetch_clients(PDO $pdo, bool $onlyActive = true): array
{
    $sql = 'SELECT * FROM clients';
    if ($onlyActive) {
        $sql .= ' WHERE active = 1';
    }
    $sql .= ' ORDER BY sort_order ASC, id ASC';
    return $pdo->query($sql)->fetchAll();
}

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Valida y mueve un archivo subido a la carpeta de uploads indicada.
 * Devuelve la ruta relativa guardada, o null si no se subió ningún archivo.
 * Lanza RuntimeException si el archivo es inválido.
 */
function handle_image_upload(array $file, string $subdir): ?string
{
    if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Error al subir el archivo.');
    }

    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!isset($allowed[$mime])) {
        throw new RuntimeException('Formato de imagen no permitido. Usa JPG, PNG o WEBP.');
    }
    if ($file['size'] > 5 * 1024 * 1024) {
        throw new RuntimeException('La imagen supera el tamaño máximo de 5MB.');
    }

    $dir = __DIR__ . '/../uploads/' . $subdir;
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    $filename = bin2hex(random_bytes(8)) . '.' . $allowed[$mime];
    $destination = $dir . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new RuntimeException('No se pudo guardar la imagen.');
    }

    return 'uploads/' . $subdir . '/' . $filename;
}

function icon_svg(string $slug): string
{
    $icons = [
        'megaphone' => '<path d="M3 11v2a1 1 0 0 0 1 1h2l5 4V6L6 10H4a1 1 0 0 0-1 1z"/><path d="M14 8a3 3 0 0 1 0 8"/><path d="M17 5a7 7 0 0 1 0 14"/>',
        'chart'     => '<path d="M4 19V5"/><path d="M4 19h16"/><rect x="7" y="11" width="3" height="6"/><rect x="12" y="7" width="3" height="10"/><rect x="17" y="14" width="3" height="3"/>',
        'handshake' => '<path d="M8 12l3 3 6-6"/><path d="M2 12h4l3-3 3 3 3-3 4 0"/><path d="M2 12v3a2 2 0 0 0 2 2h1"/><path d="M22 12v3a2 2 0 0 1-2 2h-1"/>',
        'signal'    => '<path d="M4 20h16"/><rect x="6" y="14" width="3" height="6"/><rect x="11" y="9" width="3" height="11"/><rect x="16" y="4" width="3" height="16"/>',
        'flag'      => '<path d="M5 21V4"/><path d="M5 4h13l-3 4 3 4H5"/>',
        'building'  => '<rect x="4" y="3" width="16" height="18"/><path d="M9 21v-4h6v4"/><path d="M8 7h1M8 11h1M8 15h1M15 7h1M15 11h1M15 15h1"/>',
        'badge'     => '<circle cx="12" cy="9" r="5"/><path d="M9 13.5L7 22l5-3 5 3-2-8.5"/>',
        'palette'   => '<circle cx="12" cy="12" r="9"/><circle cx="8.5" cy="10.5" r="1.2"/><circle cx="12" cy="8" r="1.2"/><circle cx="15.5" cy="10.5" r="1.2"/><path d="M12 21a9 9 0 0 1-1-17.9"/>',
    ];
    $path = $icons[$slug] ?? $icons['megaphone'];
    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $path . '</svg>';
}
