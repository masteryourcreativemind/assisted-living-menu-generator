# Assisted Living Menu Generator - Deployment Guide

## Subdomain Setup for allaround.work/tools/menugen

### DNS Configuration

For subdomain setup, add the following DNS records in your domain registrar:

```
Type: CNAME
Name: tools.allaround.work
Value: allaround.work
```

Or if using an A record:
```
Type: A
Name: tools
Value: [Your Server IP Address]
```

### Apache VirtualHost Configuration

Create a new VirtualHost configuration file `/etc/apache2/sites-available/tools.allaround.work.conf`:

```apache
<VirtualHost *:80>
    ServerName tools.allaround.work
    ServerAlias www.tools.allaround.work
    ServerAdmin admin@allaround.work
    
    # Redirect HTTP to HTTPS
    Redirect permanent / https://tools.allaround.work/
</VirtualHost>

<VirtualHost *:443>
    ServerName tools.allaround.work
    ServerAlias www.tools.allaround.work
    ServerAdmin admin@allaround.work
    
    DocumentRoot /var/www/tools.allaround.work/public_html
    
    # SSL Configuration
    SSLEngine on
    SSLCertificateFile /etc/letsencrypt/live/tools.allaround.work/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/tools.allaround.work/privkey.pem
    
    # Log files
    ErrorLog ${APACHE_LOG_DIR}/tools.allaround.work-error.log
    CustomLog ${APACHE_LOG_DIR}/tools.allaround.work-access.log combined
    
    # PHP Configuration
    <FilesMatch "\\.php$">
        SetHandler "proxy:unix:/run/php/php-fpm.sock|fcgi://localhost"
    </FilesMatch>
    
    # Directory permissions
    <Directory /var/www/tools.allaround.work/public_html>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    # Gzip compression
    <IfModule mod_deflate.c>
        AddOutputFilterByType DEFLATE text/html text/plain text/xml
    </IfModule>
</VirtualHost>
```

### Enable VirtualHost

```bash
sudo a2ensite tools.allaround.work.conf
sudo apache2ctl configtest
sudo systemctl restart apache2
```

### Directory Structure

```bash
/var/www/tools.allaround.work/
├── public_html/              # Web root
│   ├── index.php
│   ├── .htaccess
│   ├── config/
│   ├── classes/
│   ├── templates/
│   ├── assets/
│   └── data/
├── logs/                     # Application logs
├── backups/                  # Database backups
└── scripts/                  # Maintenance scripts
```

### Installation Steps

1. **Clone/Upload Project**
```bash
cd /var/www/tools.allaround.work/public_html
# Upload files here
```

2. **Set Permissions**
```bash
chmod 755 .
chmod 755 config classes templates
chmod 777 data
chmod 700 logs
```

3. **Configure PHP**
```bash
cp .env.example .env
# Edit .env with your settings
nano .env
```

4. **Setup Database** (if using MySQL)
```bash
mysql -u root -p < database/schema.sql
```

5. **Install SSL Certificate** (Let's Encrypt)
```bash
sudo certbot certonly --apache -d tools.allaround.work
```

6. **Configure Cronjobs** (for maintenance)
```bash
# Add to crontab: crontab -e
0 2 * * * /usr/bin/php /var/www/tools.allaround.work/public_html/scripts/backup.php
0 3 * * 0 /usr/bin/php /var/www/tools.allaround.work/public_html/scripts/cleanup.php
```

### Performance Optimization

1. **Enable PHP Caching**
```bash
sudo phpenmod opcache
```

2. **Configure PHP-FPM**
Edit `/etc/php/7.4/fpm/pool.d/www.conf`:
```ini
pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 10
```

3. **Enable Browser Caching**
The `.htaccess` file includes cache control headers.

4. **Database Optimization**
```sql
ANALYZE TABLE recipes;
OPTIMIZE TABLE recipes;
```

### Monitoring & Logging

1. **Error Logging**
Logs are stored in:
- `/var/log/apache2/tools.allaround.work-error.log`
- `/var/log/apache2/tools.allaround.work-access.log`

2. **Application Error Handling**
Edit `index.php` for error reporting level:
```php
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', '/var/www/tools.allaround.work/logs/error.log');
```

### Backup Strategy

Create `/var/www/tools.allaround.work/scripts/backup.php`:

```bash
#!/bin/bash
BACKUP_DIR="/var/www/tools.allaround.work/backups"
DATE=$(date +%Y%m%d_%H%M%S)
MYSQL_USER="db_user"
MYSQL_PASS="db_password"
MYSQL_DB="assisted_living_menu"

# Backup database
mysqldump -u $MYSQL_USER -p$MYSQL_PASS $MYSQL_DB > $BACKUP_DIR/db_$DATE.sql

# Backup files
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/tools.allaround.work/public_html

# Keep only last 30 days
find $BACKUP_DIR -type f -mtime +30 -delete
```

### Security Checklist

- [x] HTTPS enabled with valid SSL certificate
- [x] .htaccess configured with security headers
- [x] PHP error reporting disabled in production
- [x] Database credentials in .env (not in code)
- [x] Data directory not web-accessible
- [x] Regular backups scheduled
- [x] PHP version up to date
- [x] Input validation and sanitization
- [x] SQL injection prevention (prepared statements)
- [x] CSRF tokens on forms (if applicable)

### Testing

1. **Accessibility Test**
```bash
curl -I https://tools.allaround.work
```

2. **SSL Certificate Test**
```bash
echo | openssl s_client -servername tools.allaround.work -connect tools.allaround.work:443
```

3. **Functionality Test**
- Generate menu
- Test all export formats
- Test on mobile devices
- Verify SEO metadata

### Troubleshooting

**Issue: 500 Internal Server Error**
```bash
# Check PHP error log
tail -f /var/log/php-fpm.log
tail -f /var/log/apache2/tools.allaround.work-error.log
```

**Issue: Permission Denied**
```bash
# Verify directory ownership
ls -la /var/www/tools.allaround.work/public_html
sudo chown -R www-data:www-data /var/www/tools.allaround.work/
```

**Issue: Database Connection Error**
```bash
# Test MySQL connection
mysql -h localhost -u db_user -p db_name
# Check .env configuration
cat .env
```

### Upgrade Procedure

1. Backup current installation
2. Pull latest code
3. Run database migrations (if any)
4. Clear cache if applicable
5. Test thoroughly
6. Monitor error logs

### Support & Maintenance

- Monitor error logs weekly
- Update PHP and dependencies monthly
- Test menu generation functionality weekly
- Backup database daily
- Review access logs for suspicious activity

---

**Last Updated:** January 2026
**Compatibility:** PHP 7.4+, MySQL 5.7+, Apache 2.4+
