# Local self-signed SSL certificate (Linux)

```bash
sudo a2enmod ssl rewrite
sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
  -keyout /etc/ssl/private/myproject.local.key \
  -out /etc/ssl/certs/myproject.local.crt \
  -subj "/CN=myproject.local"
sudo chmod 600 /etc/ssl/private/myproject.local.key
sudo a2ensite myproject.local.conf
sudo apachectl configtest
sudo systemctl reload apache2
```

A self-signed certificate will show a browser trust warning unless you explicitly trust it locally. Do not use it for a public production website.
