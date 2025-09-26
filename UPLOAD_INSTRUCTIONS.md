# Manual Upload Instructions for TripMate

## Method 1: Using SCP (Secure Copy)

1. Create a zip file of your project (exclude unnecessary files):
   - Exclude: node_modules/, .git/, storage/logs/, vendor/ (will be reinstalled)

2. Upload to EC2:
```bash
# Create zip on local machine
tar -czf tripmate.tar.gz --exclude=node_modules --exclude=.git --exclude=storage/logs --exclude=vendor .

# Upload to EC2
scp -i your-key-pair.pem tripmate.tar.gz ec2-user@your-ec2-public-ip:/tmp/

# On EC2 instance:
cd /var/www/html
sudo rm -rf *
sudo tar -xzf /tmp/tripmate.tar.gz -C /var/www/html
sudo chown -R apache:apache /var/www/html
```

## Method 2: Using FileZilla or WinSCP (GUI)

1. Install FileZilla or WinSCP
2. Connect using:
   - Host: your-ec2-public-ip
   - Username: ec2-user  
   - Key file: your-key-pair.pem
3. Upload files to /home/ec2-user/
4. Move to web directory:
```bash
sudo mv /home/ec2-user/tripmate/* /var/www/html/
sudo chown -R apache:apache /var/www/html
```

## Method 3: Using AWS CodeDeploy (Advanced)

For automated deployments, consider setting up AWS CodeDeploy with GitHub integration.

## Important Files to Update After Upload:

1. Update .env file with RDS credentials
2. Run composer install
3. Run npm install && npm run build  
4. Set proper permissions
5. Run migrations