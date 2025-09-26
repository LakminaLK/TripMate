#!/bin/bash

# TripMate Laravel Production Deployment Script

echo "Starting deployment..."

# Update system packages
sudo yum update -y

# Install required packages
sudo yum install -y httpd php php-mysqlnd php-xml php-mbstring php-json php-zip git composer

# Enable and start Apache
sudo systemctl enable httpd
sudo systemctl start httpd

# Clone the repository (replace with your actual git repository)
cd /var/www/html
sudo git clone https://github.com/yourusername/tripmate.git .

# Install composer dependencies
sudo composer install --optimize-autoloader --no-dev

# Set proper permissions
sudo chown -R apache:apache /var/www/html
sudo chmod -R 755 /var/www/html
sudo chmod -R 775 /var/www/html/storage
sudo chmod -R 775 /var/www/html/bootstrap/cache

# Copy production environment file
sudo cp .env.production .env

# Generate application key
sudo php artisan key:generate --force

# Cache configurations for production
sudo php artisan config:cache
sudo php artisan route:cache
sudo php artisan view:cache

# Run database migrations
sudo php artisan migrate --force

# Create symbolic link for storage
sudo php artisan storage:link

echo "Deployment completed!"