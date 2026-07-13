<?php

/** @var PDO $pdo */
$metaTitle = fetch_content($pdo, 'meta_title', 'NG Media');
$metaDescription = fetch_content($pdo, 'meta_description', 'Agencia de publicidad, mercadeo, relaciones públicas y marketing político en República Dominicana.');
$siteTitle = $metaTitle ?: 'NG Media';
$siteDescription = $metaDescription ?: 'Agencia de publicidad, mercadeo, relaciones públicas y marketing político en República Dominicana.';
$canonicalUrl = 'https://ngmedia.do/';
$ogImageUrl = 'https://ngmedia.do/assets/img/logo-transparent.png';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($siteTitle) ?></title>
    <meta name="description" content="<?= e($siteDescription) ?>">
    <meta name="robots" content="index,follow,max-snippet:-1,max-image-preview:large,max-video-preview:-1">
    <meta name="theme-color" content="#0A2540">
    <link rel="canonical" href="<?= e($canonicalUrl) ?>">
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= e($siteTitle) ?>">
    <meta property="og:description" content="<?= e($siteDescription) ?>">
    <meta property="og:url" content="<?= e($canonicalUrl) ?>">
    <meta property="og:site_name" content="NG Media">
    <meta property="og:image" content="<?= e($ogImageUrl) ?>">
    <meta property="og:image:alt" content="NG Media agencia de publicidad y marketing político">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($siteTitle) ?>">
    <meta name="twitter:description" content="<?= e($siteDescription) ?>">
    <meta name="twitter:image" content="<?= e($ogImageUrl) ?>">
    <link rel="icon" href="assets/img/favicon.svg" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "NG Media",
        "url": "https://ngmedia.do/",
        "logo": "https://ngmedia.do/assets/img/logo-transparent.png",
        "description": "Agencia de publicidad, mercadeo, relaciones públicas y marketing político en República Dominicana.",
        "areaServed": {
            "@type": "Country",
            "name": "República Dominicana"
        }
    }
    </script>
</head>

<body>
    <a class="skip-link" href="#main">Saltar al contenido</a>
    <header class="site-header" id="site-header">
        <div class="container header-inner">
            <a href="#inicio" class="brand">
                <img src="assets/img/logo-transparent.png" alt="NG Media" class="brand-logo">
            </a>
            <nav class="main-nav" id="main-nav" aria-label="Navegación principal">
                <a href="#inicio">Inicio</a>
                <a href="#servicios">Servicios</a>
                <a href="#nosotros">Nosotros</a>
                <a href="#portafolio">Portafolio</a>
                <a href="#clientes">Clientes</a>
                <a href="#contacto">Contacto</a>
            </nav>
            <a href="#contacto" class="btn btn-accent nav-cta">Solicitar Cotización</a>
            <button class="nav-toggle" id="nav-toggle" aria-label="Abrir menú" aria-expanded="false" aria-controls="main-nav">
                <span></span><span></span><span></span>
            </button>
        </div>
    </header>
    <main id="main">