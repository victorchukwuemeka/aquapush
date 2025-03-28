<?php

namespace App\Services;

class CloudInitService
{
    public function generateCloudInitScript()
    {
        return <<<'EOT'
#cloud-config
package_update: true
package_upgrade: true

write_files:
  - path: /var/www/html/install.php
    permissions: '0640'
    owner: root:www-data
    content: |
      <?php
       $API_KEY = 'YOUR_SECRET_KEY';
       $headers = getallheaders();

      function logMessage($message) {
          $logFile = '/var/www/html/install_debug.log';
          $timestamp = date('Y-m-d H:i:s');
          file_put_contents($logFile, "[$timestamp] $message" . PHP_EOL, FILE_APPEND | LOCK_EX);
      }

      if (!isset($headers['X-API-KEY']) || $headers['X-API-KEY'] !== $API_KEY) {
          http_response_code(403);
          logMessage("Unauthorized access attempt from IP: " . $_SERVER['REMOTE_ADDR']);
          die(json_encode(["status" => "error", "message" => "Unauthorized"]));
      }

      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          $data = json_decode(file_get_contents('php://input'), true);
          if (json_last_error() !== JSON_ERROR_NONE || empty($data['repo'])) {
              http_response_code(400);
              logMessage("Invalid request data");
              die(json_encode(["status" => "error", "message" => "Invalid request"]));
          }

          $repo = escapeshellarg($data['repo']);
          $projectDir = '/var/www/html/repo';

          try {
              // 1. Create directory with sudo fallback
              if (!@mkdir($projectDir, 0775, true) && !is_dir($projectDir)) {
                  exec('sudo mkdir -p ' . escapeshellarg($projectDir) . ' 2>&1', $output, $returnCode);
                  if ($returnCode !== 0) {
                      throw new \RuntimeException("Directory creation failed: " . implode("\n", $output));
                  }
                  exec('sudo chown www-data:www-data ' . escapeshellarg($projectDir) . ' 2>&1');
              }

              // 2. Clone repository
              exec('sudo -u www-data git clone ' . $repo . ' ' . escapeshellarg($projectDir) . ' 2>&1', $output, $returnCode);
              if ($returnCode !== 0) {
                  throw new \RuntimeException("Git clone failed: " . implode("\n", $output));
              }

              // 3. Composer install
              chdir($projectDir);
              if (file_exists('composer.json')) {
                  putenv('PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin');
                  putenv('HOME=/var/www');
                  exec('sudo -u www-data /usr/local/bin/composer install --no-dev --optimize-autoloader 2>&1', $output, $returnCode);
                  if ($returnCode !== 0) {
                      throw new \RuntimeException("Composer install failed: " . implode("\n", $output));
                  }
              }

              // 4. Laravel setup (if artisan exists)
              if (file_exists('artisan')) {
                  if (!file_exists('.env') && file_exists('.env.example')) {
                      if (!copy('.env.example', '.env')) {
                          throw new \RuntimeException("Failed to create .env file");
                      }
                  }
                  
                  exec('sudo -u www-data php artisan key:generate --force 2>&1', $output, $returnCode);
                  if ($returnCode !== 0) {
                      throw new \RuntimeException("Key generation failed: " . implode("\n", $output));
                  }

                  exec('sudo -u www-data php artisan migrate --force 2>&1', $output, $returnCode);
                  if ($returnCode !== 0) {
                      throw new \RuntimeException("Migrations failed: " . implode("\n", $output));
                  }
              }

              // 5. Set permissions
              exec('sudo chown -R www-data:www-data ' . escapeshellarg($projectDir) . ' 2>&1');
              exec('sudo find ' . escapeshellarg($projectDir) . ' -type d -exec chmod 750 {} \\; 2>&1');
              exec('sudo find ' . escapeshellarg($projectDir) . ' -type f -exec chmod 640 {} \\; 2>&1');

              // 6. Restart Apache
              exec('sudo systemctl restart apache2 2>&1', $output, $returnCode);
              if ($returnCode !== 0) {
                  throw new \RuntimeException("Apache restart failed: " . implode("\n", $output));
              }

              echo json_encode(["status" => "success", "message" => "Setup completed"]);
              
          } catch (\Exception $e) {
              http_response_code(500);
              logMessage("Error: " . $e->getMessage());
              echo json_encode(["status" => "error", "message" => $e->getMessage()]);
          }
      }
      ?>

runcmd:
  - |
    # Set up logging
    mkdir -p /var/log
    LOG_FILE="/var/log/cloud-init-output.log"
    echo "==== STARTING INSTALLATION ====" > $LOG_FILE

    # 1. INSTALL APACHE FIRST
    echo "==== INSTALLING APACHE ====" >> $LOG_FILE
    export DEBIAN_FRONTEND=noninteractive
    apt-get update -y >> $LOG_FILE 2>&1
    apt-get install -y apache2 libapache2-mod-php >> $LOG_FILE 2>&1
    
    # Verify Apache
    if ! systemctl is-active apache2 >> $LOG_FILE 2>&1; then
        echo "Apache failed to start!" >> $LOG_FILE
        journalctl -u apache2 --no-pager >> $LOG_FILE
        exit 1
    fi

    # 2. INSTALL PHP 8.3
    echo "==== INSTALLING PHP ====" >> $LOG_FILE
    add-apt-repository ppa:ondrej/php -y >> $LOG_FILE 2>&1
    apt-get install -y \
        php8.3 php8.3-cli php8.3-common php8.3-mbstring \
        php8.3-xml php8.3-curl php8.3-mysql php8.3-zip \
        php8.3-bcmath php8.3-intl php8.3-opcache >> $LOG_FILE 2>&1

    # 3. BULLETPROOF COMPOSER INSTALL
    echo "==== INSTALLING COMPOSER ====" >> $LOG_FILE
    
    # Clean previous attempts
    rm -f /usr/local/bin/composer /usr/bin/composer /tmp/composer-setup.php
    
    # Wait for PHP to be ready
    for i in {1..5}; do
        if php -v &>/dev/null; then
            echo "PHP verified (attempt $i)" >> $LOG_FILE
            break
        elif [ $i -eq 5 ]; then
            echo "ERROR: PHP not available after 5 attempts" >> $LOG_FILE
            exit 1
        else
            echo "Waiting for PHP... (attempt $i)" >> $LOG_FILE
            sleep 3
        fi
    done
    
    # Install with proper environment
    (
        export HOME=/root
        mkdir -p /usr/local/bin
        
        # Download with retries
        for i in {1..3}; do
            if curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php >> $LOG_FILE 2>&1; then
                break
            elif [ $i -eq 3 ]; then
                echo "ERROR: Failed to download Composer" >> $LOG_FILE
                exit 1
            fi
            sleep 5
        done
        
        # Install
        php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer >> $LOG_FILE 2>&1 || {
            echo "ERROR: Composer installation failed" >> $LOG_FILE
            exit 1
        }
        
        rm -f /tmp/composer-setup.php
        chmod +x /usr/local/bin/composer
        ln -sf /usr/local/bin/composer /usr/bin/composer
    )
    
    # Configure www-data environment
    mkdir -p /var/www/.composer
    chown -R www-data:www-data /var/www/.composer
    echo 'export HOME=/var/www' >> /etc/profile
    echo 'export PATH=$PATH:/usr/local/bin' >> /etc/profile
    
    # Verify installation
    if ! sudo -u www-data bash -c 'source /etc/profile && composer --version' >> $LOG_FILE 2>&1; then
        echo "!!!! COMPOSER INSTALLATION FAILED !!!!" >> $LOG_FILE
        echo "Debug info:" >> $LOG_FILE
        {
            echo "Composer path: $(which composer)"
            echo "www-data PATH: $(sudo -u www-data bash -c 'echo $PATH')"
            ls -la /usr/local/bin/composer
        } >> $LOG_FILE
        exit 1
    fi

    # 4. SET UP WWW-DATA USER
    echo "==== CONFIGURING PERMISSIONS ====" >> $LOG_FILE
    if ! id www-data >/dev/null 2>&1; then
        groupadd -r www-data
        useradd -r -g www-data -d /var/www -s /usr/sbin/nologin www-data
    fi

    # 5. CONFIGURE WEB DIRECTORY
    mkdir -p /var/www/html
    chown -R www-data:www-data /var/www
    chmod -R 775 /var/www

    # 6. CONFIGURE SUDO
    echo 'www-data ALL=(ALL) NOPASSWD: /usr/bin/git, /usr/local/bin/composer, /usr/bin/php, /bin/mkdir, /bin/chmod, /bin/chown, /bin/systemctl restart apache2' > /etc/sudoers.d/www-data
    chmod 440 /etc/sudoers.d/www-data

    # 7.  APACHE CONFIG
    a2enmod rewrite >> $LOG_FILE 2>&1
    systemctl restart apache2 >> $LOG_FILE 2>&1

    #8. INSTALL MARIADB AND CONFIGURE IT
    apt-get install -y mariadb-server mariadb-client || apt-get install -y mysql-server mysql-client

    # Start and verify
    systemctl start mariadb || systemctl start mysql
    systemctl enable mariadb || systemctl enable mysql
    mysql --version || mariadb --version

    #automate the installation security
    mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED BY 'your_strong_password';"
    mysql -e "DELETE FROM mysql.user WHERE User='';"
    mysql -e "DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');"
    mysql -e "DROP DATABASE IF EXISTS test;"
    mysql -e "FLUSH PRIVILEGES;"
    
    #create the database 
    mysql -e "CREATE DATABASE aquapush;"
    mysql -e "GRANT ALL ON aquapush.* TO 'aquapush_user'@'localhost' IDENTIFIED BY 'another_strong_password';"
    
    #update the .env 
    sed -i "s/DB_HOST=.*/DB_HOST=127.0.0.1/" /var/www/html/repo/.env
    sed -i "s/DB_DATABASE=.*/DB_DATABASE=aquapush/" /var/www/html/repo/.env 
    sed -i "s/DB_USERNAME=.*/DB_USERNAME=aquapush_user/" /var/www/html/repo/.env
    sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=another_strong_password/" /var/www/html/repo/.env
    
    # 8. VERIFICATION
    echo "==== VERIFICATION ====" >> $LOG_FILE
    {
        echo "Apache: $(systemctl is-active apache2)"
        echo "PHP: $(php -v | head -n1)"
        echo "Composer: $(composer --version)"
        echo "Web root: $(ls -ld /var/www/html)"
        echo "==== INSTALLATION COMPLETE ===="
    } >> $LOG_FILE
EOT;
    }
}