# Testing Your TripMate Production Deployment

## 1. Basic Connectivity Tests

### Test HTTP Access
```bash
# Test from local machine
curl -I http://your-ec2-public-ip
# Should return 200 OK

# Test specific endpoint
curl http://your-ec2-public-ip/login
```

### Test Database Connection
```bash
# On EC2 instance
cd /var/www/html
sudo php artisan tinker

# In tinker prompt:
DB::connection()->getPdo();
# Should return PDO object if connection successful
```

## 2. Application Functionality Tests

### Test User Registration
- Visit: http://your-domain.com/register  
- Try registering a new user
- Check if user is created in database

### Test Login System
- Visit: http://your-domain.com/login
- Test login with created user
- Verify session management

### Test File Uploads (if applicable)
- Test image uploads
- Verify files are stored correctly
- Check permissions on storage directories

## 3. Performance Tests

### Basic Load Test
```bash
# Install Apache Bench (if not available)
sudo yum install httpd-tools

# Test with 100 concurrent requests
ab -n 1000 -c 100 http://your-domain.com/
```

### Database Performance
```bash
# Check MySQL performance
sudo mysql -h your-rds-endpoint -u admin -p
SHOW PROCESSLIST;
SHOW STATUS LIKE 'Connections%';
```

## 4. Security Tests

### Check SSL Configuration
```bash
# Test SSL certificate
openssl s_client -connect your-domain.com:443 -servername your-domain.com
```

### Check File Permissions
```bash
# On EC2 instance
ls -la /var/www/html/
# storage and bootstrap/cache should be writable by apache
```

## 5. Monitoring Setup

### Check Log Files
```bash
# Apache logs
sudo tail -f /var/log/httpd/access_log
sudo tail -f /var/log/httpd/error_log

# Laravel logs
sudo tail -f /var/www/html/storage/logs/laravel.log
```

### System Resource Usage
```bash
# Check memory usage
free -h

# Check disk usage  
df -h

# Check CPU usage
top
```

## 6. Common Issues and Solutions

### Issue: 500 Internal Server Error
**Solutions:**
```bash
# Check Laravel logs
sudo tail -50 /var/www/html/storage/logs/laravel.log

# Check Apache error logs
sudo tail -50 /var/log/httpd/error_log

# Common fixes:
sudo chmod -R 775 /var/www/html/storage
sudo chmod -R 775 /var/www/html/bootstrap/cache
sudo chown -R apache:apache /var/www/html
```

### Issue: Database Connection Failed
**Solutions:**
```bash
# Verify RDS endpoint and credentials in .env
sudo nano /var/www/html/.env

# Test database connection
sudo php artisan migrate:status

# Check security groups allow connection from EC2 to RDS
```

### Issue: Assets Not Loading (CSS/JS)
**Solutions:**
```bash
# Rebuild assets
cd /var/www/html
sudo npm run build

# Check storage link
sudo php artisan storage:link

# Clear caches
sudo php artisan cache:clear
sudo php artisan view:clear
```

## 7. Post-Deployment Checklist

- [ ] Application loads without errors
- [ ] User registration works
- [ ] User login works  
- [ ] Database operations function
- [ ] File uploads work (if applicable)
- [ ] SSL certificate is valid
- [ ] Domain name resolves correctly
- [ ] Backup procedures tested
- [ ] Monitoring alerts configured
- [ ] Performance benchmarks recorded

## 8. Maintenance Commands

### Regular Maintenance
```bash
# Clear application caches
sudo php artisan cache:clear
sudo php artisan view:clear
sudo php artisan route:clear
sudo php artisan config:clear

# Update application (when needed)
cd /var/www/html
sudo git pull origin main
sudo composer install --optimize-autoloader --no-dev
sudo npm run build
sudo php artisan migrate --force
sudo php artisan config:cache
sudo php artisan route:cache
sudo php artisan view:cache
sudo systemctl restart httpd
```