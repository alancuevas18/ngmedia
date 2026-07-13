<?php
require __DIR__ . '/config/database.php';
require __DIR__ . '/includes/functions.php';

$services = fetch_services($pdo);
$portfolioItems = fetch_portfolio($pdo);
$clients = fetch_clients($pdo);

$formStatus = $_GET['status'] ?? null;

require __DIR__ . '/includes/header.php';
?>

<section class="hero" id="inicio">
    <div class="container hero-inner reveal">
        <h1><?= e(fetch_content($pdo, 'hero_title')) ?></h1>
        <p><?= e(fetch_content($pdo, 'hero_subtitle')) ?></p>
        <div class="hero-actions">
            <a href="#contacto" class="btn btn-accent">Solicitar Cotización</a>
            <a href="#servicios" class="btn btn-outline">Ver Servicios</a>
        </div>
    </div>
</section>

<section id="nosotros">
    <div class="container">
        <div class="about-grid">
            <div class="reveal">
                <span class="eyebrow">Sobre Nosotros</span>
                <h2>Construimos la mejor imagen para tu marca</h2>
                <p><?= e(fetch_content($pdo, 'about_text')) ?></p>
            </div>
            <div class="value-cards reveal">
                <div class="value-card">
                    <h4>Misión</h4>
                    <p><?= e(fetch_content($pdo, 'mission_text')) ?></p>
                </div>
                <div class="value-card">
                    <h4>Visión</h4>
                    <p><?= e(fetch_content($pdo, 'vision_text')) ?></p>
                </div>
                <div class="value-card">
                    <h4>Valores</h4>
                    <p><?= e(fetch_content($pdo, 'values_text')) ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-alt" id="servicios">
    <div class="container">
        <div class="section-head reveal">
            <span class="eyebrow">Lo que hacemos</span>
            <h2>Nuestros Servicios</h2>
            <p>Soluciones integrales de comunicación para marcas, instituciones y campañas.</p>
        </div>
        <div class="services-grid">
            <?php foreach ($services as $service): ?>
            <div class="service-card reveal">
                <div class="service-icon"><?= icon_svg($service['icon_slug']) ?></div>
                <h3><?= e($service['title']) ?></h3>
                <p><?= e($service['description']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="political-banner reveal">
            <div>
                <h2>Marketing Político y Gubernamental</h2>
                <p>Nuestra mayor experiencia: estrategia, ejecución y gestión de imagen para campañas políticas y entidades del gobierno en toda República Dominicana.</p>
            </div>
            <a href="#contacto" class="btn btn-primary">Conversemos</a>
        </div>
    </div>
</section>

<section class="section-alt" id="clientes">
    <div class="container">
        <div class="section-head reveal">
            <span class="eyebrow">Confían en Nosotros</span>
            <h2>Nuestros Clientes</h2>
        </div>
        <div class="clients-grid reveal">
            <?php foreach ($clients as $client): ?>
            <div class="client-logo">
                <img src="<?= e($client['logo_path']) ?>" alt="<?= e($client['name']) ?>" loading="lazy">
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="portafolio">
    <div class="container">
        <div class="section-head reveal">
            <span class="eyebrow">Nuestro Trabajo</span>
            <h2>Portafolio de Proyectos</h2>
        </div>
        <div class="portfolio-filters reveal">
            <button class="filter-btn is-active" data-filter="all">Todos</button>
            <button class="filter-btn" data-filter="Político">Político</button>
            <button class="filter-btn" data-filter="Gubernamental">Gubernamental</button>
            <button class="filter-btn" data-filter="Corporativo">Corporativo</button>
        </div>
        <div class="portfolio-grid">
            <?php foreach ($portfolioItems as $item): ?>
            <div class="portfolio-card reveal" data-category="<?= e($item['category']) ?>">
                <img src="<?= e($item['image_path']) ?>" alt="<?= e($item['title']) ?>" loading="lazy">
                <div class="portfolio-overlay">
                    <span><?= e($item['category']) ?></span>
                    <h4><?= e($item['title']) ?></h4>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section-alt" id="contacto">
    <div class="container">
        <div class="section-head reveal">
            <span class="eyebrow">Hablemos</span>
            <h2>Solicita tu Cotización</h2>
            <p>Cuéntanos sobre tu proyecto y te contactaremos a la brevedad.</p>
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
