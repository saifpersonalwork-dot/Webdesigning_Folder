# MCA108P – Lab Sheet 9: PHP & MySQL Solutions

## Requirements
- PHP 8+ with mysqli
- MySQL 8+
- Apache (XAMPP/WAMP/LAMP)

## Setup
1. Copy this folder into your web server's document root (for XAMPP: `htdocs`).
2. Import `database.sql` using phpMyAdmin or the MySQL command line.
3. Edit `config/db.php` with your MySQL username/password.
4. Open `http://localhost/MCA108P_AS09_Solution/register.php`.
5. Register a user, log in, and open the dashboard.

## Files
- `database.sql`: creates `webapp_db`, `users`, `products`, and `orders`.
- `config/db.php`: reusable mysqli connection (Q1–2).
- `register.php`, `login.php`, `dashboard.php`, `logout.php`: authentication (Q3–6).
- `product_add.php`, `products.php`, `product_edit.php`, `product_delete.php`: CRUD (Q7–10).
- `products_paginated.php`, `products_search.php`, `products_sort.php`: pagination, filtering, sorting (Q11–13).
- `product_upload.php`: validated image upload (Q14).
- `sql_injection_demo.php`: vulnerable vs prepared query demonstration (Q15).
- `xss_demo.php`: safe output escaping (Q16).
- `remember_me.php`: cookie-based Remember Me demonstration (Q17).
- `cart.php`, `checkout.php`: session cart and transactional checkout (Q18–19).
- `live_search.php`, `search_suggestions.php`: AJAX live search (Q20).
- `includes/`: shared helpers and authentication checks.

For a classroom demonstration, these examples use simple session authentication. In a real deployment, use HTTPS, CSRF tokens on every state-changing form, secure cookie settings, rate limiting, and a production-grade secret-management strategy.
