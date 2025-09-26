#!/bin/bash

# Server Setup Script for Amazon Linux 2023

echo "=== Starting TripMate Server Setup ==="

# Update system
sudo yum update -y

# Install Apache web server
sudo yum install -y httpd

# Install PHP 8.2 and extensions
sudo yum install -y php php-cli php-fpm php-mysqlnd php-zip php-devel php-gd php-mcrypt php-mbstring php-curl php-xml php-pear php-bcmath php-json

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

# Install Git
sudo yum install -y git

# Install Node.js and npm (for asset compilation)
curl -fsSL https://rpm.nodesource.com/setup_18.x | sudo bash -
sudo yum install -y nodejs

# Start and enable Apache
sudo systemctl start httpd
sudo systemctl enable httpd

# Start and enable PHP-FPM
sudo systemctl start php-fpm
sudo systemctl enable php-fpm

echo "=== Server setup completed ==="

# Create Apache virtual host configuration
sudo cat > /etc/httpd/conf.d/tripmate.conf << 'EOF'
<VirtualHost *:80>
    DocumentRoot /var/www/html/public
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com

    <Directory /var/www/html/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog /var/log/httpd/tripmate_error.log
    CustomLog /var/log/httpd/tripmate_access.log combined
</VirtualHost>
EOF

echo "=== Apache configured ==="

# Set proper PHP configuration for production
sudo sed -i 's/;date.timezone =/date.timezone = Asia\/Colombo/' /etc/php.ini
sudo sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 20M/' /etc/php.ini
sudo sed -i 's/post_max_size = 8M/post_max_size = 20M/' /etc/php.ini
sudo sed -i 's/max_execution_time = 30/max_execution_time = 60/' /etc/php.ini
sudo sed -i 's/memory_limit = 128M/memory_limit = 256M/' /etc/php.ini

# Restart Apache to apply changes
sudo systemctl restart httpd

echo "=== PHP configured ==="
echo "Server is ready for application deployment!"