# EC2 Setup Commands for TripMate
# Run these commands one by one after connecting to your EC2 instance

# 1. Update system packages
sudo yum update -y

# 2. Install Apache web server
sudo yum install -y httpd

# 3. Install PHP 8.2 and required extensions
sudo yum install -y php php-cli php-fpm php-mysqlnd php-zip php-devel php-gd php-mbstring php-curl php-xml php-pear php-bcmath php-json

# 4. Install Composer (PHP package manager)
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

# 5. Install Git
sudo yum install -y git

# 6. Install Node.js and npm (for building assets)
curl -fsSL https://rpm.nodesource.com/setup_18.x | sudo bash -
sudo yum install -y nodejs

# 7. Start and enable Apache
sudo systemctl start httpd
sudo systemctl enable httpd

# 8. Start and enable PHP-FPM
sudo systemctl start php-fpm
sudo systemctl enable php-fpm

# 9. Configure Apache for Laravel
sudo tee /etc/httpd/conf.d/laravel.conf > /dev/null <<EOF
<VirtualHost *:80>
    DocumentRoot /var/www/html/public
    
    <Directory /var/www/html/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog /var/log/httpd/laravel_error.log
    CustomLog /var/log/httpd/laravel_access.log combined
</VirtualHost>
EOF

# 10. Configure PHP settings for production
sudo sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 20M/' /etc/php.ini
sudo sed -i 's/post_max_size = 8M/post_max_size = 20M/' /etc/php.ini
sudo sed -i 's/max_execution_time = 30/max_execution_time = 60/' /etc/php.ini
sudo sed -i 's/memory_limit = 128M/memory_limit = 256M/' /etc/php.ini

# 11. Restart Apache to apply changes
sudo systemctl restart httpd

echo "✅ Server setup completed! Ready for application deployment."