# TripMate EC2 Deployment & Troubleshooting Guide

## 🚀 Quick Reference for EC2 Deployment

This guide provides step-by-step instructions for connecting to your AWS EC2 instance, deploying code changes, and troubleshooting common issues.

---

## 📋 Server Information
- **Instance Name**: TripMate-Server
- **Instance ID**: i-0a4cc5b0c2af01dc8
- **Public IP**: 43.205.215.239
- **Instance Type**: t3.micro
- **OS**: Amazon Linux 2023
- **Web Server**: Apache (httpd)
- **PHP**: PHP-FPM
- **Project Path**: `/var/www/tripmate`

---

## 🔐 1. Connecting to EC2 Instance

### Using PowerShell (Windows)
```powershell
# Navigate to your key file location
cd "C:\Users\YourUser\path\to\key"

# Connect using SSH
ssh -i "tripmate-key.pem" ec2-user@43.205.215.239
```

### Common Connection Issues & Solutions

#### Issue: "Connection timed out"
**Solution**: Check Security Group settings
1. Go to AWS Console → EC2 → Security Groups
2. Find your instance's security group
3. Ensure these inbound rules exist:
   - SSH (Port 22): Your IP or 0.0.0.0/0
   - HTTP (Port 80): 0.0.0.0/0
   - HTTPS (Port 443): 0.0.0.0/0

#### Issue: "Permission denied (publickey)"
**Solution**: Check key file permissions
```powershell
# In PowerShell, ensure key file has correct permissions
icacls "tripmate-key.pem" /inheritance:r /grant:r "%username%:R"
```

---

## 🔧 2. Git Operations & File Deployment

### Navigate to Project Directory
```bash
cd /var/www/tripmate
```

### Fix Git Permissions (if needed)
```bash
# Add directory as safe for git
git config --global --add safe.directory /var/www/tripmate

# Fix ownership if git operations fail
sudo chown -R ec2-user:ec2-user /var/www/tripmate
```

### Deploy Specific Files Only
```bash
# IMPORTANT: Fix git permissions first (if you get permission denied errors)
sudo chown -R ec2-user:ec2-user /var/www/tripmate
sudo chmod -R 755 /var/www/tripmate/.git

# Check current git status
git status

# Fetch latest changes from GitHub
git fetch origin main

# Pull specific file (e.g., landing page)
git checkout origin/main -- resources/views/tourist/landing.blade.php

# For multiple specific files:
git checkout origin/main -- resources/views/tourist/landing.blade.php resources/views/components/tourist/header.blade.php

# Verify file was updated
ls -la resources/views/tourist/landing.blade.php
ls -la resources/views/components/tourist/header.blade.php
```

### Deploy All Changes (use with caution)
```bash
# Stash any local changes (if needed)
git stash

# Pull all changes
git pull origin main

# Apply stashed changes back (if needed)
git stash pop
```

---

## 🛠️ 3. Permission Management

### Check Current Web Server Process
```bash
ps aux | grep -E "(apache|httpd|nginx)"
```

### Fix File Ownership & Permissions
```bash
# Set correct ownership (apache user for web files)
sudo chown -R apache:apache /var/www/tripmate

# Set directory permissions
sudo chmod -R 755 /var/www/tripmate

# Set Laravel-specific permissions
sudo chmod -R 775 /var/www/tripmate/storage
sudo chmod -R 775 /var/www/tripmate/bootstrap/cache

# Fix .env file permissions
sudo chmod 644 /var/www/tripmate/.env
```

---

## 🧹 4. Laravel Cache Management

### Clear All Caches
```bash
cd /var/www/tripmate

# Clear application cache
php artisan cache:clear

# Clear configuration cache
php artisan config:clear

# Clear view cache
php artisan view:clear

# Clear route cache (if using route caching)
php artisan route:clear
```

### Restart Services
```bash
# Restart Apache web server
sudo systemctl restart httpd

# Check Apache status
sudo systemctl status httpd

# Restart PHP-FPM (if needed)
sudo systemctl restart php-fpm
```

---

## 🚨 5. Troubleshooting Common Issues

### Issue: HTTP 500 Error

#### Step 1: Check Apache Error Logs
```bash
# View recent errors
sudo tail -20 /var/log/httpd/error_log

# View access logs
sudo tail -20 /var/log/httpd/access_log

# Monitor logs in real-time
sudo tail -f /var/log/httpd/error_log
```

#### Step 2: Check Laravel Logs
```bash
# View Laravel application logs
tail -20 /var/www/tripmate/storage/logs/laravel.log

# Monitor Laravel logs in real-time
tail -f /var/www/tripmate/storage/logs/laravel.log
```

#### Step 3: Fix Common Causes
```bash
# Fix permissions
sudo chown -R apache:apache /var/www/tripmate
sudo chmod -R 755 /var/www/tripmate
sudo chmod -R 775 /var/www/tripmate/storage
sudo chmod -R 775 /var/www/tripmate/bootstrap/cache

# Clear caches
cd /var/www/tripmate
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Restart services
sudo systemctl restart httpd
```

### Issue: "Permission denied" for Git Operations

#### Common Error Messages:
- `error: cannot open '.git/FETCH_HEAD': Permission denied`
- `fatal: Unable to create '/var/www/tripmate/.git/index.lock': Permission denied`

#### Solution:
```bash
# Step 1: Temporarily give git permissions to ec2-user
sudo chown -R ec2-user:ec2-user /var/www/tripmate

# Step 2: Fix git directory permissions specifically
sudo chmod -R 755 /var/www/tripmate/.git

# Step 3: Add as safe directory (if needed)
git config --global --add safe.directory /var/www/tripmate

# Step 4: Now try git operations
git fetch origin main
git checkout origin/main -- your-file-path

# Step 5: After git operations, restore web server ownership
sudo chown -R apache:apache /var/www/tripmate
```

#### Why This Happens:
- The web server (apache) owns the files for security
- Git operations need to be done by ec2-user
- We temporarily switch ownership for git, then switch back

### Issue: Database Connection Errors
```bash
# Check .env file exists and has correct permissions
ls -la /var/www/tripmate/.env
cat /var/www/tripmate/.env | grep DB_

# Test database connection
cd /var/www/tripmate
php artisan tinker
# In tinker: DB::connection()->getPdo();
```

---

## 📁 6. File Structure Reference

```
/var/www/tripmate/
├── app/                    # Laravel application files
├── bootstrap/              # Bootstrap files (cache here)
├── config/                 # Configuration files
├── database/               # Database files
├── public/                 # Web-accessible files
├── resources/              # Views, CSS, JS
│   └── views/
│       └── tourist/
│           └── landing.blade.php  # Main landing page
├── routes/                 # Route definitions
├── storage/                # Storage & logs (needs write permissions)
├── vendor/                 # Composer dependencies
├── .env                    # Environment configuration
└── artisan                 # Laravel command line tool
```

---

## ⚡ 7. Quick Commands Cheat Sheet

### Connection
```bash
ssh -i "tripmate-key.pem" ec2-user@43.205.215.239
```

### Navigation
```bash
cd /var/www/tripmate
```

### Complete Deployment Workflow (Recommended)
```bash
# Step 1: Fix permissions for git
sudo chown -R ec2-user:ec2-user /var/www/tripmate

# Step 2: Deploy files
git fetch origin main
git checkout origin/main -- resources/views/tourist/landing.blade.php resources/views/components/tourist/header.blade.php

# Step 3: Restore web server permissions
sudo chown -R apache:apache /var/www/tripmate && sudo chmod -R 755 /var/www/tripmate && sudo chmod -R 775 /var/www/tripmate/storage /var/www/tripmate/bootstrap/cache

# Step 4: Clear cache and restart
php artisan cache:clear && php artisan config:clear && php artisan view:clear && sudo systemctl restart httpd
```

### View Logs
```bash
sudo tail -20 /var/log/httpd/error_log
```

---

## 🔍 8. Health Check Commands

### Check Website Status
```bash
# Test if website responds
curl -I http://43.205.215.239

# Check if specific page loads
curl http://43.205.215.239
```

### Check Services
```bash
# Check Apache status
sudo systemctl status httpd

# Check PHP-FPM status
sudo systemctl status php-fpm

# Check disk space
df -h

# Check memory usage
free -h
```

---

## 📝 9. Deployment Best Practices

### Before Making Changes
1. ✅ Test changes locally first
2. ✅ Commit and push to GitHub
3. ✅ Take note of current working state
4. ✅ Have backup plan ready

### During Deployment
1. ✅ Connect to EC2
2. ✅ Navigate to project directory
3. ✅ Check git status
4. ✅ Pull only specific files (safer than full pull)
5. ✅ Fix permissions
6. ✅ Clear caches
7. ✅ Test website

### After Deployment
1. ✅ Verify website loads correctly
2. ✅ Check for any errors in logs
3. ✅ Test key functionality
4. ✅ Monitor for any issues

---

## 🆘 Emergency Recovery

### If Website Goes Down
```bash
# Emergency recovery with permission fix
cd /var/www/tripmate
sudo chown -R apache:apache /var/www/tripmate
sudo chmod -R 755 /var/www/tripmate
sudo chmod -R 775 /var/www/tripmate/storage /var/www/tripmate/bootstrap/cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
sudo systemctl restart httpd
```

### Complete Emergency Deployment Fix
```bash
# If you need to redeploy AND fix permissions in one go
cd /var/www/tripmate
sudo chown -R ec2-user:ec2-user /var/www/tripmate
git fetch origin main
git checkout origin/main -- resources/views/tourist/landing.blade.php resources/views/components/tourist/header.blade.php
sudo chown -R apache:apache /var/www/tripmate
sudo chmod -R 755 /var/www/tripmate
sudo chmod -R 775 /var/www/tripmate/storage /var/www/tripmate/bootstrap/cache
php artisan cache:clear && php artisan config:clear && php artisan view:clear
sudo systemctl restart httpd
```

### Rollback Changes
```bash
# View recent commits
git log --oneline -10

# Rollback to previous commit (replace COMMIT_HASH)
git checkout COMMIT_HASH -- resources/views/tourist/landing.blade.php

# Or reset to last known good state
git checkout HEAD~1 -- resources/views/tourist/landing.blade.php
```

---

## 📞 Additional Resources

- **AWS Console**: https://console.aws.amazon.com/
- **GitHub Repository**: https://github.com/LakminaLK/TripMate
- **Laravel Documentation**: https://laravel.com/docs

---

*Last Updated: October 6, 2025*
*Instance: TripMate-Server (43.205.215.239)*