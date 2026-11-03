# Apache, HTTPS and Virtual Host (Lab Sheet 10, Q17–19)

## Virtual host
Add this to Apache's `httpd-vhosts.conf` (adjust paths):

```apache
<VirtualHost *:80>
    ServerName myproject.local
    DocumentRoot "C:/xampp/htdocs/Web_Lab_Solutions/public"
    <Directory "C:/xampp/htdocs/Web_Lab_Solutions/public">
        AllowOverride All
        Require all granted
    </Directory>
    ErrorLog "logs/myproject-error.log"
    CustomLog "logs/myproject-access.log" common
</VirtualHost>
```

Add this line to the system hosts file as administrator:
`127.0.0.1 myproject.local`

Restart Apache and open `http://myproject.local/`.

## HTTPS local test
1. Enable `mod_ssl` and `mod_socache_shmcb` in Apache.
2. Generate a self-signed certificate for local testing using OpenSSL/Apache's SSL tools.
3. Configure a `<VirtualHost *:443>` with `SSLEngine on`, `SSLCertificateFile` and
   `SSLCertificateKeyFile` pointing to your certificate and key.
4. Restart Apache and visit `https://myproject.local/`. The browser will warn because
   a self-signed certificate is not publicly trusted.
5. Set `session_secure` to `true` in `config/config.php` after HTTPS works.
6. Uncomment the HTTPS redirect rules in `public/.htaccess`.

Do not use a self-signed certificate on a public website.
