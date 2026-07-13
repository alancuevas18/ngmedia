<?php
require __DIR__ . '/config/database.php';
require __DIR__ . '/includes/functions.php';

$pageTitle = 'Contacto | Agencia de Publicidad y Marketing en República Dominicana';
$pageDescription = 'Ponte en contacto con NG Media para servicios de publicidad, marketing político, branding y comunicación institucional en Santo Domingo y toda República Dominicana.';
$canonicalUrl = 'https://ngmedia.do/contacto';
$ogImageUrl = 'https://ngmedia.do/assets/img/logo-transparent.png';
$additionalSchema = <<<JSON
{
  "@context": "https://schema.org",
  "@type": "ContactPage",
  "name": "Contacto NG Media",
  "url": "https://ngmedia.do/contacto",
  "description": "Formulario de contacto para solicitar cotización de servicios de publicidad, marketing político y comunicación institucional.",
  "provider": {
    "@type": "Organization",
    "name": "NG Media"
  }
}
JSON;

$formStatus = $_GET['status'] ?? null;
require __DIR__ . '/includes/header.php';
?>

<section class="hero" id="inicio" aria-labelledby="contact-page-title">
    <div class="container hero-inner reveal">
        <p class="eyebrow">Contacto</p>
        <h1 id="contact-page-title">Contáctanos para tu próxima campaña de publicidad o marketing</h1>
        <p>Solicita una cotización para branding, publicidad, marketing político, comunicación institucional o estrategias digitales en República Dominicana.</p>
        <div class="hero-pill-group">
            <span class="hero-pill">Respuesta rápida</span>
            <span class="hero-pill">Presupuestos claros</span>
            <span class="hero-pill">Soporte dedicado</span>
        </div>
        <div class="hero-actions">
            <a href="mailto:info@ngmedia.do" class="btn btn-accent">Escribir por Email</a>
            <a href="servicios.php" class="btn btn-outline">Ver Servicios</a>
        </div>
    </div>
</section>

<section id="contacto" aria-labelledby="contact-title">
    <div class="container">
        <div class="section-head reveal">
            <span class="eyebrow">Hablemos</span>
            <h2 id="contact-title">Solicita tu cotización y te responderemos pronto</h2>
            <p>Cuéntanos sobre tus objetivos, tu público y el tipo de campaña que deseas desarrollar. Nos pondremos en contacto para ayudarte a lograrlo.</p>
        </div>
        <div class="contact-grid">
            <div class="reveal">
                <div class="contact-info-item">
                    <div>
                        <strong>Teléfono</strong>
                        <span><?= e(fetch_content($pdo, 'contact_phone')) ?></span>
                    </div>
                </div>
                <div class="contact-info-item">
                    <div>
                        <strong>Email</strong>
                        <span><?= e(fetch_content($pdo, 'contact_email')) ?></span>
                    </div>
                </div>
                <div class="contact-info-item">
                    <div>
                        <strong>Dirección</strong>
                        <span><?= e(fetch_content($pdo, 'contact_address')) ?></span>
                    </div>
                </div>
            </div>
            <form class="contact-form reveal" action="contact-submit.php" method="post" novalidate>
                <?php if ($formStatus === 'success'): ?>
                    <div class="form-alert success">¡Gracias! Tu mensaje fue enviado. Te contactaremos pronto.</div>
                <?php elseif ($formStatus === 'error'): ?>
                    <div class="form-alert error">Ocurrió un error al enviar tu mensaje. Verifica los campos e intenta de nuevo.</div>
                <?php endif; ?>
                <div class="form-row">
                    <div>
                        <label for="name">Nombre</label>
                        <input type="text" id="name" name="name" required maxlength="150">
                    </div>
                    <div>
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required maxlength="150">
                    </div>
                </div>
                <div>
                    <label for="phone">Teléfono</label>
                    <input type="tel" id="phone" name="phone" maxlength="50">
                </div>
                <div>
                    <label for="message">Mensaje</label>
                    <textarea id="message" name="message" rows="5" required maxlength="2000"></textarea>
                </div>
                <input type="text" name="website" class="form-honeypot" tabindex="-1" autocomplete="off">
                <button type="submit" class="btn btn-accent">Enviar Mensaje</button>
            </form>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>