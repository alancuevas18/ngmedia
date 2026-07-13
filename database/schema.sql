-- NG Media — esquema de base de datos (MySQL / MariaDB)
-- Uso: mysql -u root -p < database/schema.sql

CREATE DATABASE IF NOT EXISTS ngmedia CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ngmedia;

-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS admin_users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Usuario inicial: admin / NGMedia2026! (cambiar de inmediato tras el primer login)
INSERT INTO admin_users (username, password_hash) VALUES
    ('admin', '$2y$12$tDL7DlsKaoI0jpma0w2Zze5S0wJKPwrGukoOcQkLlXtnks6Obg7hm')
ON DUPLICATE KEY UPDATE username = username;

-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS services (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    icon_slug VARCHAR(50) NOT NULL DEFAULT 'megaphone',
    description TEXT,
    sort_order INT UNSIGNED NOT NULL DEFAULT 0,
    active TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;

INSERT INTO services (title, icon_slug, description, sort_order) VALUES
    ('Publicidad', 'megaphone', 'Campañas publicitarias creativas y efectivas para posicionar tu marca.', 1),
    ('Mercadeo e Investigación', 'chart', 'Estudios de mercado y estrategias basadas en datos reales.', 2),
    ('Relaciones Públicas', 'handshake', 'Gestión de imagen y comunicación estratégica con tus públicos.', 3),
    ('Publicidad Digital', 'signal', 'Presencia y pauta digital en redes sociales y medios online.', 4),
    ('Marketing Político', 'flag', 'Estrategia y ejecución de campañas políticas y gubernamentales.', 5),
    ('Marketing Gubernamental', 'building', 'Comunicación institucional para entidades del gobierno.', 6),
    ('Identidad Corporativa', 'badge', 'Diseño de marca, rotulación, editorial, impresos y textiles.', 7),
    ('Diseño y Rotulación', 'palette', 'Piezas gráficas, señalética y material POP para tu negocio.', 8)
ON DUPLICATE KEY UPDATE title = title;

-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS portfolio_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    category ENUM('Político', 'Gubernamental', 'Corporativo') NOT NULL DEFAULT 'Corporativo',
    image_path VARCHAR(255) NOT NULL,
    description TEXT,
    sort_order INT UNSIGNED NOT NULL DEFAULT 0,
    active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO portfolio_items (title, category, image_path, description, sort_order) VALUES
    ('Campaña Institucional', 'Gubernamental', 'assets/img/placeholder-portfolio.svg', 'Proyecto de comunicación institucional.', 1),
    ('Estrategia Electoral', 'Político', 'assets/img/placeholder-portfolio.svg', 'Campaña de posicionamiento político.', 2),
    ('Identidad de Marca', 'Corporativo', 'assets/img/placeholder-portfolio.svg', 'Rediseño de identidad corporativa.', 3)
ON DUPLICATE KEY UPDATE title = title;

-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS clients (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    logo_path VARCHAR(255) NOT NULL,
    sort_order INT UNSIGNED NOT NULL DEFAULT 0,
    active TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;

INSERT INTO clients (name, logo_path, sort_order) VALUES
    ('Cámara de Diputados', 'assets/img/placeholder-logo.svg', 1),
    ('CORAASAN', 'assets/img/placeholder-logo.svg', 2),
    ('INDOCAL', 'assets/img/placeholder-logo.svg', 3),
    ('UNIBE', 'assets/img/placeholder-logo.svg', 4),
    ('Cemex', 'assets/img/placeholder-logo.svg', 5),
    ('Ayuntamientos', 'assets/img/placeholder-logo.svg', 6)
ON DUPLICATE KEY UPDATE name = name;

-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(50),
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    read_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS site_content (
    content_key VARCHAR(100) PRIMARY KEY,
    content_value TEXT
) ENGINE=InnoDB;

INSERT INTO site_content (content_key, content_value) VALUES
    ('hero_title', 'Somos tu aliado para tu mejor imagen'),
    ('hero_subtitle', 'Publicidad, Mercadeo, Relaciones Públicas y Marketing Político en República Dominicana'),
    ('about_text', 'En NG Media combinamos creatividad, estrategia y experiencia para construir la mejor imagen de marcas, instituciones y figuras políticas en República Dominicana.'),
    ('mission_text', 'Ofrecer soluciones de comunicación integrales que generen resultados medibles y confianza duradera.'),
    ('vision_text', 'Ser la agencia de referencia en publicidad y marketing político-gubernamental en el país.'),
    ('values_text', 'Integridad, creatividad, compromiso y excelencia en cada proyecto.'),
    ('contact_phone', '+1 (809) 000-0000'),
    ('contact_email', 'info@ngmedia.do'),
    ('contact_address', 'Santo Domingo, República Dominicana'),
    ('whatsapp_number', '18090000000'),
    ('facebook_url', ''),
    ('instagram_url', ''),
    ('linkedin_url', ''),
    ('youtube_url', ''),
    ('meta_title', 'NG Media | Agencia de Publicidad y Marketing Político en Santo Domingo'),
    ('meta_description', 'Agencia de publicidad, mercadeo, relaciones públicas y marketing político en República Dominicana. Solicita tu cotización.')
ON DUPLICATE KEY UPDATE content_key = content_key;
