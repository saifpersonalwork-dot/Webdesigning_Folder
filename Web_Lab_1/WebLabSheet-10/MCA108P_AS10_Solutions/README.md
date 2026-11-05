
# MCA108P – Lab Sheet 10 Solutions

This package contains a practical PHP MVC application skeleton and supporting files for all 20 lab-sheet tasks.

## Requirements
- PHP 8.1+ with PDO and PDO MySQL extensions
- MySQL 8+
- Apache 2.4 with `mod_rewrite` and SSL enabled
- Composer (for PHPMailer)
- Git

## Setup
1. Copy the `project/` folder into your Apache document root (or configure the supplied virtual host).
2. Create a MySQL database and a restricted application user using `database/setup.sql`.
3. Copy `project/config/config.example.php` to `project/config/config.php` and edit the credentials.
4. From the project directory run `composer install`.
5. Point the Apache document root to `project/public`.
6. Visit `https://myproject.local/` after configuring the local virtual host and hosts file.

## Task mapping
- Tasks 1–2: `git/commands.md`
- Tasks 3–6: MVC layout, router, PDO singleton, model, and reusable views in `project/`
- Tasks 7–8: REST API and JavaScript fetch client in `project/public/api/` and `project/public/assets/`
- Tasks 9–16: CSRF, secure sessions, validation, login throttling, password reset, PHPMailer, uploads, and error handling in `project/`
- Tasks 17–19: `.htaccess`, SSL, and virtual-host examples in `apache/`
- Task 20: restricted MySQL account in `database/setup.sql`

## Security notes
- Replace all example secrets and tokens before deployment.
- Set `secure => true` for cookies only when serving the site over HTTPS.
- Configure SMTP credentials through environment variables; do not commit them.
- This is a teaching starter project. Review and test it before production use.
