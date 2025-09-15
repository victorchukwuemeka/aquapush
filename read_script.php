<?php

namespace App\Services;

class CloudInitService
{
    public function generateCloudInitScript()
    {
        return <<<EOT
#cloud-config
write_files:
  - path: /var/www/html/install.php
    permissions: '0640'
    owner: root:www-data
    content: |
      <?php
      \$API_KEY = 'YOUR_SECRET_KEY';
      \$headers = getallheaders();

      function logMessage(\$message) {
          \$logFile = '/var/www/html/install_debug.log';
          \$timestamp = date('Y-m-d H:i:s');
          file_put_contents(\$logFile, "[\$timestamp] \$message" . PHP_EOL, FILE_APPEND | LOCK_EX);
      }

      if (!isset(\$headers['X-API-KEY']) || \$headers['X-API-KEY'] !== \$API_KEY) {
          http_response_code(403);
          logMessage("Unauthorized access attempt from IP: " . \$_SERVER['REMOTE_ADDR']);
          die(json_encode(["status" => "error", "message" => "Unauthorized"]));
      }

      if (\$_SERVER['REQUEST_METHOD'] === 'POST') {
          \$data = json_decode(file_get_contents('php://input'), true);
          if (json_last_error() !== JSON_ERROR_NONE || empty(\$data['repo'])) {
              http_response_code(400);
              logMessage("Invalid request data");
              die(json_encode(["status" => "error", "message" => "Invalid request"]));
          }

          \$repo = escapeshellarg(\$data['repo']);
          \$projectDir = '/var/www/html/repo';

          try {
              if (!mkdir(\$projectDir, 0750, true) && !is_dir(\$projectDir)) {
                  throw new \RuntimeException("Failed to create directory");
              }

              exec("sudo -u www-data git clone \$repo \$projectDir 2>&1", \$output, \$returnCode);
              if (\$returnCode !== 0) {
                  throw new \RuntimeException("Git clone failed: " . implode("\\n", \$output));
              }

              chdir(\$projectDir);
              if (file_exists('composer.json')) {
                  // Use full path to composer
                  passthru('sudo -u www-data /usr/local/bin/composer install --no-dev --optimize-autoloader 2>&1', \$returnCode);
                  if (\$returnCode !== 0) {
                      throw new \RuntimeException("Composer install failed");
                  }
              }

              if (file_exists('artisan')) {
                  if (!file_exists('.env') && file_exists('.env.example')) {
                      copy('.env.example', '.env');
                  }
                  
                  passthru('sudo -u www-data php artisan key:generate --force 2>&1', \$returnCode);
                  if (\$returnCode !== 0) {
                      throw new \RuntimeException("Key generation failed");
                  }

                  passthru('sudo -u www-data php artisan migrate --force 2>&1', \$returnCode);
                  if (\$returnCode !== 0) {
                      throw new \RuntimeException("Migrations failed");
                  }
              }

              passthru('sudo chown -R www-data:www-data ' . escapeshellarg(\$projectDir) . ' 2>&1');
              passthru('sudo find ' . escapeshellarg(\$projectDir) . ' -type d -exec chmod 750 {} \\; 2>&1');
              passthru('sudo find ' . escapeshellarg(\$projectDir) . ' -type f -exec chmod 640 {} \\; 2>&1');

              passthru('sudo systemctl restart apache2 2>&1', \$returnCode);
              if (\$returnCode !== 0) {
                  throw new \RuntimeException("Apache restart failed");
              }

              echo json_encode(["status" => "success", "message" => "Setup completed"]);
              
          } catch (\Exception \$e) {
              http_response_code(500);
              logMessage("Error: " . \$e->getMessage());
              echo json_encode(["status" => "error", "message" => \$e->getMessage()]);
          }
      }
      ?>

runcmd:
  - |
    export DEBIAN_FRONTEND=noninteractive
    apt-get update -y
    apt-get install -y software-properties-common
    add-apt-repository ppa:ondrej/php -y
    apt-get update -y

    apt-get install -y \
        php8.3 php8.3-cli php8.3-common php8.3-mbstring \
        php8.3-xml php8.3-curl php8.3-mysql php8.3-zip \
        php8.3-bcmath php8.3-intl php8.3-opcache \
        apache2 libapache2-mod-php8.3 git unzip

    # Install Composer globally
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
    chmod +x /usr/local/bin/composer
    
    # Add composer to www-data's PATH
    echo 'export PATH=\$PATH:/usr/local/bin' >> /etc/profile
    echo 'export PATH=\$PATH:/usr/local/bin' >> /var/www/.bashrc
    
    # Make sure www-data can access the profile
    chown www-data:www-data /var/www/.bashrc
    chmod 644 /var/www/.bashrc

    a2enmod rewrite
    systemctl enable apache2
    systemctl restart apache2

    chown -R www-data:www-data /var/www/html
    find /var/www/html -type d -exec chmod 750 {} \\;
    find /var/www/html -type f -exec chmod 640 {} \\;

    echo 'www-data ALL=(ALL) NOPASSWD: /usr/bin/git, /usr/local/bin/composer, /usr/bin/php, /bin/systemctl restart apache2' > /etc/sudoers.d/www-data
    chmod 440 /etc/sudoers.d/www-data

    sed -i 's/^display_errors = .*/display_errors = Off/' /etc/php/8.3/apache2/php.ini
    systemctl restart apache2

    apt-get autoremove -y
    apt-get clean
EOT;
    }
}