# Salon Exclusive
A full-stack PHP website for **Salon Exclusive** — hair care, styling, and branded product lines (**Re-born**, **STAS**, **Logomatter**). The public site showcases products and blog content; the admin panel handles products, posts, orders, and contact inquiries.
---
## Features
### Public website
- Responsive landing page with hero video and product highlights
- Dedicated pages for **Re-born** and **STAS** product lines
- Product detail pages with clean URLs (`/product/{id}`)
- Blog listing and single-post views
- Contact form with inquiry types stored in the database
### Admin panel (`/admin/`)
- Dashboard with overview statistics
- CRUD for products (categories: `reborn`, `stas`, `logomatter`)
- Blog post management (draft / published / archived)
- Logomatter order tracking
- Contact inquiry inbox with status workflow
- Media upload helpers for blog content
See [admin/README.md](admin/README.md) for admin-specific documentation.
---
## Tech stack
| Layer | Technologies |
|--------|----------------|
| Backend | PHP 7.4+, PDO (MySQL) |
| Database | MySQL / MariaDB (`utf8mb4`) |
| Frontend | HTML, CSS (Bootstrap-based theme), JavaScript (jQuery, Owl Carousel, AOS, etc.) |
| Styles | Compiled `css/style.css` (source SCSS in `scss/`) |
| Server | Apache with `mod_rewrite` (optional pretty URLs) |
No Composer or Node build step is required to run the site; assets are committed as static files.
---
## Requirements
- PHP **7.4+** with PDO MySQL extension
- MySQL **5.7+** or MariaDB **10.3+**
- Apache (or nginx with equivalent rewrite rules) + PHP
- Writable `uploads/` directory for admin media uploads
---
## Quick start
### 1. Clone the repository
```bash
git clone https://github.com/YOUR_USERNAME/salon-exclusive.git
cd salon-exclusive
```
Replace the URL with your actual GitHub remote.
### 2. Web root
Point your virtual host document root to this folder (e.g. `public_html`), or copy the project into your server’s web directory:
- **XAMPP (macOS):** `htdocs/saloon/` → `http://localhost/saloon/`
- **Shared hosting:** often `public_html/` at the domain root
If the site lives in a subdirectory, uncomment and set `RewriteBase` in [.htaccess](.htaccess).
### 3. Database setup
Create the database and import the schema. Two SQL files are provided:
| File | Notes |
|------|--------|
| [database_setup_v2.sql](database_setup_v2.sql) | Recommended clean schema + sample data (`salon_exclusive_v2`) |
| [database_setup.sql](database_setup.sql) | Legacy/alternate setup if you already use that structure |
**Example (local MySQL):**
```bash
mysql -u root -p < database_setup_v2.sql
```
Or import via phpMyAdmin.
### 4. Configure database connection
Edit [config/database.php](config/database.php) and set your environment values:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'salon_exclusive_v2');  // or your database name
define('DB_USER', 'your_db_user');
define('DB_PASS', 'your_db_password');
define('DB_CHARSET', 'utf8mb4');
```
On macOS XAMPP, the project optionally uses the MySQL socket at  
`/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock` when that file exists; otherwise it connects via `localhost:3306`.
> **Security:** Do not commit real production passwords. Use local credentials for development and configure production secrets on the server only.
### 5. File permissions
Ensure the web server can write to upload directories:
```bash
chmod -R 755 uploads/
```
### 6. Open the site
| URL | Purpose |
|-----|---------|
| `/` or `/index.php` | Home |
| `/reborn.php`, `/stas.php` | Product lines |
| `/blog.php` | Blog |
| `/contact.php` | Contact |
| `/admin/` | Admin login |
**Default admin credentials (change before production):**
- Username: `admin`
- Password: `salon2024`
Update authentication in [admin/login.php](admin/login.php) and use password hashing for production deployments.
---
## Project structure
```
.
├── index.php              # Home
├── reborn.php             # Re-born products
├── stas.php               # STAS products
├── product.php            # Product detail (also /product/{id})
├── blog.php               # Blog index
├── blog-single.php        # Single post
├── contact.php            # Contact form
├── config/
│   └── database.php       # DB config + shared query helpers
├── admin/                 # Admin panel (see admin/README.md)
├── css/                   # Compiled styles
├── js/                    # Frontend scripts
├── scss/                  # SCSS sources
├── uploads/               # User-uploaded media (blog, etc.)
├── database_setup.sql
├── database_setup_v2.sql
└── .htaccess              # Apache rewrite rules
```
---
## URL rewriting
With Apache `mod_rewrite` enabled, product pages can use:
```
/product/123  →  product.php?id=123
```
Rules are defined in [.htaccess](.htaccess).
---
## Deployment checklist
- [ ] Import database and update [config/database.php](config/database.php)
- [ ] Change default admin username/password
- [ ] Restrict or protect `/admin/` (HTTPS, strong passwords, optional IP allowlist)
- [ ] Set correct `RewriteBase` if not at domain root
- [ ] Confirm `uploads/` is writable and backed up
- [ ] Disable display of PHP errors in production (`display_errors=Off`)
---
## Development notes
- Main styles: edit [scss/style.scss](scss/style.scss) and recompile to `css/style.css`, or edit `css/style.css` directly if you do not use a SCSS workflow.
- [prepros-6.config](prepros-6.config) references a Prepros setup if you use that tool.
- Blog and product images may use paths under `uploads/` or theme `images/` depending on how content was created in admin.
---
## License
Specify your license here (e.g. MIT, proprietary). If this repository is private business code, state **All rights reserved**.
---