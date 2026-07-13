<?php
/** @var PDO $pdo */
$phone = fetch_content($pdo, 'contact_phone');
$email = fetch_content($pdo, 'contact_email');
$address = fetch_content($pdo, 'contact_address');
$whatsapp = fetch_content($pdo, 'whatsapp_number');
$facebook = fetch_content($pdo, 'facebook_url');
$instagram = fetch_content($pdo, 'instagram_url');
$linkedin = fetch_content($pdo, 'linkedin_url');
$youtube = fetch_content($pdo, 'youtube_url');
?>
</main>

<footer class="site-footer">
    <div class="container footer-grid">
        <div>
            <img src="assets/img/logo-white.svg" alt="NG Media" class="footer-logo">
            <p>Tu aliado en publicidad, mercadeo, relaciones públicas y marketing político en República Dominicana.</p>
        </div>
        <div>
            <h4>Contacto</h4>
            <ul class="footer-list">
                <?php if ($phone): ?><li><?= e($phone) ?></li><?php endif; ?>
                <?php if ($email): ?><li><a href="mailto:<?= e($email) ?>"><?= e($email) ?></a></li><?php endif; ?>
                <?php if ($address): ?><li><?= e($address) ?></li><?php endif; ?>
            </ul>
        </div>
        <div>
            <h4>Síguenos</h4>
            <div class="social-links">
                <?php if ($facebook): ?><a href="<?= e($facebook) ?>" target="_blank" rel="noopener">Facebook</a><?php endif; ?>
                <?php if ($instagram): ?><a href="<?= e($instagram) ?>" target="_blank" rel="noopener">Instagram</a><?php endif; ?>
                <?php if ($linkedin): ?><a href="<?= e($linkedin) ?>" target="_blank" rel="noopener">LinkedIn</a><?php endif; ?>
                <?php if ($youtube): ?><a href="<?= e($youtube) ?>" target="_blank" rel="noopener">YouTube</a><?php endif; ?>
            </div>
        </div>
    </div>
    <div class="container footer-bottom">
        <p>&copy; <?= date('Y') ?> NG Media. Todos los derechos reservados.</p>
    </div>
</footer>

<?php if ($whatsapp): ?>
<a class="whatsapp-fab" href="https://wa.me/<?= e($whatsapp) ?>" target="_blank" rel="noopener" aria-label="Escríbenos por WhatsApp">
    <svg viewBox="0 0 32 32" fill="currentColor" aria-hidden="true"><path d="M16.02 3C9.4 3 4 8.4 4 15.02c0 2.3.63 4.45 1.72 6.3L4 29l7.86-1.66a12.9 12.9 0 0 0 4.16.7C22.6 28.04 28 22.64 28 16.02 28 9.4 22.64 3 16.02 3zm0 23a10.9 10.9 0 0 1-5.56-1.53l-.4-.24-4.66.98.99-4.55-.26-.41A10.94 10.94 0 1 1 27 16.02 10.98 10.98 0 0 1 16.02 26zm6.02-8.2c-.33-.16-1.95-.96-2.25-1.07-.3-.11-.52-.16-.74.16-.22.33-.85 1.07-1.04 1.28-.19.22-.38.24-.71.08-.33-.16-1.4-.52-2.66-1.65-.98-.87-1.65-1.95-1.84-2.28-.19-.33-.02-.5.14-.67.15-.15.33-.38.5-.57.16-.19.22-.33.33-.55.11-.22.05-.41-.03-.57-.08-.16-.74-1.78-1.01-2.44-.27-.64-.54-.55-.74-.56h-.63c-.22 0-.57.08-.87.41-.3.33-1.14 1.11-1.14 2.71 0 1.6 1.17 3.14 1.33 3.36.16.22 2.3 3.51 5.57 4.92.78.34 1.38.54 1.86.69.78.25 1.49.21 2.05.13.63-.09 1.95-.8 2.22-1.57.28-.77.28-1.43.2-1.57-.08-.14-.3-.22-.63-.38z"/></svg>
</a>
<?php endif; ?>

<script src="assets/js/main.js"></script>
</body>
</html>
