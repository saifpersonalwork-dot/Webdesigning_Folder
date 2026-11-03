# Lab Sheet 10 — PHP, MVC, Git and API Solutions

This project covers the Lab Sheet 10 application tasks: MVC structure, front controller,
PDO singleton, reusable templates, REST API, bearer-token authentication, CSRF,
secure sessions, input validation, login limiting, password reset, PHPMailer integration,
secure uploads, error pages/logging, Apache clean URLs, and database least privilege.

## Git workflow
Commands and the deliberate merge-conflict exercise are in `docs/GIT_WORKFLOW.md`.

## Apache setup
Virtual host, local HTTPS and rewrite guidance are in `docs/APACHE_SETUP.md`.

## Setup
1. Install PHP 8.1+, MySQL/MariaDB, and Apache (XAMPP is suitable).
2. Import `database/schema.sql` in phpMyAdmin.
3. Set database credentials in `config/config.php`.
4. Put the folder in your Apache document root and open `public/`.
5. Register a user and log in. The API token is shown once on the login page after registration.

See the main project README in the combined project for additional security notes.
