<?php
require __DIR__ . '/config/database.php';
require __DIR__ . '/includes/functions.php';

$services = fetch_services($pdo);
$pageTitle = 'Servicios de Publicidad, Marketing y Comunicación Estratégica | NG Media';
$pageDescription = 'Descubre nuestros servicios de publicidad, marketing político, relaciones públicas, branding y comunicación institucional en República Dominicana.';
$canonicalUrl = 'https://ngmedia.do/servicios';
$ogImageUrl = 'https://ngmedia.do/assets/img/logo-transparent.png';
$additionalSchema = <<<JSON
{
  "@context": "https://schema.org",
  "@type": "Service",
  "name": "Servicios de publicidad, marketing y comunicación estratégica",
  "serviceType": "Agencia de publicidad y marketing",
  "provider": {
    "@type": "Organization",
    "name": "NG Media"
  },
  "areaServed": {
    "@type": "Country",
    "name": "República Dominicana"
  },
  "description": "Servicios de publicidad, marketing político, relaciones públicas, branding y comunicación institucional en República Dominicana."
}
JSON;

require __DIR__ . '/includes/header.php';
?>

<section class="hero" id="inicio" aria-labelledby="services-page-title">
    <div class="container hero-inner reveal">
        <p class="eyebrow">Servicios</p>
        <h1 id="services-page-title">Servicios de publicidad, marketing y comunicación estratégica</h1>
        <p>Diseñamos estrategias de comunicación para marcas, organizaciones, instituciones y campañas políticas que necesitan crecer, diferenciarse y conectar mejor con su audiencia.</p>
        <div class="hero-actions">
            <a href="/#contacto" class="btn btn-accent">Solicitar Cotización</a>
            <a href="/#portafolio" class="btn btn-outline">Ver Portafolio</a>
        </div>
    </div>
</section>

<section id="servicios" aria-labelledby="services-list-title">
    <div class="container">
        <div class="section-head reveal">
            <span class="eyebrow">Nuestra Oferta</span>
            <h2 id="services-list-title">Soluciones integrales para fortalecer tu imagen y tus resultados</h2>
            <p>Nos enfocamos en dar claridad, estrategia y presencia a cada proyecto para que cada mensaje llegue con impacto.</p>
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

<section class="section-alt" aria-labelledby="why-us-title">
    <div class="container">
        <div class="about-grid">
            <div class="reveal">
                <span class="eyebrow">Por qué elegirnos</span>
                <h2 id="why-us-title">Estrategia, creatividad y ejecución alineadas con tus objetivos</h2>
                <p>En NG Media combinamos análisis, creatividad y experiencia para construir mensajes claros y campañas que generan confianza y resultados.</p>
            </div>
            <div class="value-cards reveal">
                <div class="value-card">
                    <h3>Planificación estratégica</h3>
                    <p>Definimos objetivos, audiencias y mensajes para cada proyecto.</p>
                </div>
                <div class="value-card">
                    <h3>Ejecutamos con precisión</h3>
                    <p>Gestionamos campañas y contenidos con enfoque en calidad y cumplimiento.</p>
                </div>
                <div class="value-card">
                    <h3>Medición y mejora continua</h3>
                    <p>Monitoreamos el impacto para ajustar la estrategia en cada etapa.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
