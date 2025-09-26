#!/bin/bash

# TripMate Application Deployment Script
# Run this script on your EC2 instance after server setup

echo "=== Starting Application Deployment ==="

# Navigate to web directory
cd /var/www/html

# Remove default Apache content
sudo rm -rf *

# Method 1: Upload via Git (Recommended)
# Replace with your actual repository URL
echo "Cloning repository..."
sudo git clone https://github.com/yourusername/tripmate.git .

# Method 2: Alternative - if you don't have git repository
# You can upload files using SCP or SFTP
# scp -r -i your-key.pem /path/to/tripmate/* ec2-user@your-ec2-ip:/tmp/
# sudo mv /tmp/* /var/www/html/

# Install PHP dependencies
echo "Installing Composer dependencies..."
sudo composer install --optimize-autoloader --no-dev

# Install Node.js dependencies and build assets
echo "Building frontend assets..."
sudo npm install
sudo npm run build

# Set up environment file
echo "Configuring environment..."
sudo cp .env.production .env

# Update .env with actual RDS endpoint
# You'll need to replace these with your actual values
sudo sed -i 's/your-rds-endpoint.amazonaws.com/YOUR_ACTUAL_RDS_ENDPOINT/' .env
sudo sed -i 's/your-secure-password/YOUR_ACTUAL_DB_PASSWORD/' .env
sudo sed -i 's/yourdomain.com/YOUR_ACTUAL_DOMAIN/' .env

# Generate application key
echo "Generating application key..."
sudo php artisan key:generate --force

# Set proper file permissions
echo "Setting file permissions..."
sudo chown -R apache:apache /var/www/html
sudo chmod -R 755 /var/www/html
sudo chmod -R 775 /var/www/html/storage
sudo chmod -R 775 /var/www/html/bootstrap/cache

# Cache configurations for production
echo "Caching configurations..."
sudo php artisan config:cache
sudo php artisan route:cache
sudo php artisan view:cache

# Create storage symbolic link
echo "Creating storage link..."
sudo php artisan storage:link

# Run database migrations
echo "Running database migrations..."
sudo php artisan migrate --force

# Restart Apache
sudo systemctl restart httpd

echo "=== Application Deployment Completed ==="
echo "Your application should now be accessible via your EC2 public IP address"