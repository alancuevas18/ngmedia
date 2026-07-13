# NG Media — Sitio Web + Panel de Administración

Sitio web para NG Media (agencia de publicidad, mercadeo, relaciones públicas y marketing político en República Dominicana), construido en PHP nativo + MySQL con panel de administración incluido.

## Requisitos

- PHP 8.0+
- MySQL o MariaDB
- Servidor Apache (con `mod_rewrite`) o Nginx equivalente para producción

## Instalación local

1. Crear la base de datos y las tablas con datos semilla:
   ```
   mysql -u root -p < database/schema.sql
   ```
2. Configurar la conexión. Por defecto `config/database.php` lee variables de entorno con estos valores por defecto:
   - `DB_HOST` = 127.0.0.1
   - `DB_NAME` = ngmedia
   - `DB_USER` = root
   - `DB_PASS` = (vacío)
   - `DB_PORT` = 3306

   Ajusta esas variables de entorno o edita directamente `config/database.php` con tus credenciales.

3. Levantar un servidor de desarrollo:
   ```
   php -S localhost:8000
   ```
4. Visitar `http://localhost:8000` para el sitio público y `http://localhost:8000/admin/login.php` para el panel de administración.

## Acceso inicial al panel admin

- **Usuario:** `admin`
- **Contraseña:** `NGMedia2026!`

**Cambia esta contraseña de inmediato** después del primer login (puedes generar un nuevo hash con `password_hash()` en PHP y actualizarlo directamente en la tabla `admin_users`, o agregar una pantalla de cambio de contraseña).

## Estructura

- `index.php` — homepage pública (hero, servicios, nosotros, clientes, portafolio, contacto).
- `contact-submit.php` — procesa el formulario de contacto.
- `admin/` — panel de administración (login, dashboard, CRUD de servicios/portafolio/clientes, mensajes, ajustes del sitio).
- `includes/` — helpers y partials compartidos (`functions.php`, `header.php`, `footer.php`).
- `config/database.php` — conexión PDO a MySQL.
- `database/schema.sql` — esquema completo + datos semilla.
- `assets/` — CSS, JS e imágenes (placeholders SVG incluidos: reemplázalos con fotos/logos reales).
- `uploads/` — imágenes subidas desde el panel admin (portafolio y clientes).

## Contenido editable desde el panel admin

- Servicios (título, ícono, descripción, orden, activo/inactivo).
- Portafolio (título, categoría, imagen, descripción).
- Clientes (nombre, logo).
- Mensajes de contacto recibidos.
- Textos generales del sitio: hero, sobre nosotros, misión/visión/valores, datos de contacto, redes sociales y meta tags SEO.

## Notas de seguridad

- Todas las consultas usan sentencias preparadas PDO.
- Las contraseñas se almacenan con `password_hash()` (bcrypt).
- Las subidas de imágenes validan tipo MIME real y tamaño máximo (5MB), y no se puede ejecutar PHP dentro de `/uploads`.
- `/config`, `/database` e `/includes` están bloqueados vía `.htaccess`.

## Pendiente antes de producción

- Reemplazar los placeholders SVG en `assets/img/` con el logo real y fotos de campañas.
- Configurar HTTPS y credenciales de base de datos reales en el hosting.
- Cambiar la contraseña del usuario admin inicial.
- Integrar Google Analytics / Meta Pixel si se desea (agregar el script en `includes/header.php` o `footer.php`).
