# Final Configuration Checklist for TripMate Production

## 1. Database Configuration
After RDS is ready, update your .env file on EC2:

```bash
# SSH into your EC2 instance
ssh -i your-key-pair.pem ec2-user@your-ec2-public-ip

# Edit environment file
sudo nano /var/www/html/.env

# Update these values with your actual RDS details:
DB_HOST=your-actual-rds-endpoint.amazonaws.com
DB_DATABASE=tripmate_production  
DB_USERNAME=admin
DB_PASSWORD=your-actual-database-password
```

## 2. Run Database Setup
```bash
# Run migrations
sudo php artisan migrate --force

# (Optional) Seed initial data if you have seeders
sudo php artisan db:seed --force
```

## 3. Configure File Uploads (S3 - Optional)
If using S3 for file storage, update .env:

```bash
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your-access-key
AWS_SECRET_ACCESS_KEY=your-secret-key  
AWS_DEFAULT_REGION=your-region
AWS_BUCKET=your-bucket-name
```

## 4. Set Up Cron Jobs for Laravel Scheduler
```bash
# Edit crontab
sudo crontab -e

# Add this line:
* * * * * cd /var/www/html && php artisan schedule:run >> /dev/null 2>&1
```

## 5. Configure Log Rotation
```bash
# Create logrotate configuration
sudo nano /etc/logrotate.d/laravel

# Add content:
/var/www/html/storage/logs/*.log {
    daily
    missingok
    rotate 52
    compress
    delaycompress
    notifempty
    create 644 apache apache
}
```

## 6. Security Hardening
```bash
# Disable directory browsing
sudo nano /etc/httpd/conf/httpd.conf
# Change "Options Indexes FollowSymLinks" to "Options FollowSymLinks"

# Hide server information
echo "ServerTokens Prod" | sudo tee -a /etc/httpd/conf/httpd.conf
echo "ServerSignature Off" | sudo tee -a /etc/httpd/conf/httpd.conf

# Restart Apache
sudo systemctl restart httpd
```

## 7. Set Up Monitoring
Consider setting up:
- CloudWatch for server monitoring
- Application performance monitoring
- Database monitoring
- Log aggregation

## 8. Backup Strategy
- RDS automated backups are enabled
- Consider additional backup solutions for application files
- Test restore procedures

## 9. Performance Optimization
```bash
# Enable OPcache for PHP
sudo nano /etc/php.ini

# Uncomment and configure:
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=4000
opcache.revalidate_freq=60

# Restart services
sudo systemctl restart php-fpm
sudo systemctl restart httpd
```