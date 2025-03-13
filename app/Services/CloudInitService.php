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
    permissions: '0644'
    owner: root:root
    content: |
      <?php
      \$API_KEY = 'YOUR_SECRET_KEY';
      \$headers = getallheaders();

      // Helper function to log messages
      function logMessage(\$message) {
          \$logFile = '/var/www/html/install_debug.log';
          \$timestamp = date('Y-m-d H:i:s');
          file_put_contents(\$logFile, "[\$timestamp] \$message" . PHP_EOL, FILE_APPEND);
      }

      // Validate the X-API-KEY header
      if (!isset(\$headers['X-API-KEY']) || \$headers['X-API-KEY'] !== \$API_KEY) {
          http_response_code(403);
          \$errorMessage = "Unauthorized access attempt.";
          logMessage(\$errorMessage);
          echo json_encode(["status" => "error", "message" => \$errorMessage]);
          exit;
      }

      if (\$_SERVER['REQUEST_METHOD'] === 'POST') {
          \$data = json_decode(file_get_contents('php://input'), true);

          if (empty(\$data['repo'])) {
              \$errorMessage = "Repository URL not provided.";
              logMessage(\$errorMessage);
              echo json_encode(["status" => "error", "message" => \$errorMessage]);
              exit;
          }

          \$repo = escapeshellarg(\$data['repo']);
          \$projectDir = '/var/www/html/repo';

          // Ensure proper permissions and create the directory
          shell_exec("sudo mkdir -p \$projectDir");
          shell_exec("sudo chown -R www-data:www-data /var/www/html");
          shell_exec("sudo chmod -R 775 /var/www/html");

          // Clone the repository
          \$output = shell_exec("sudo -u www-data git clone \$repo \$projectDir 2>&1");
          logMessage("Cloning output: \$output");
          if (strpos(\$output, 'fatal:') !== false) {
              logMessage("Failed to clone repository: \$output");
              echo json_encode(["status" => "error", "message" => "Failed to clone repository", "output" => \$output]);
              exit;
          }

          // Move to the project directory
          chdir(\$projectDir);

          // Install Composer dependencies
          \$output .= shell_exec("sudo -u www-data composer install --no-interaction --no-progress 2>&1");
          logMessage("Composer output: \$output");

          // Set up environment file
          if (!file_exists('.env')) {
              shell_exec("sudo -u www-data cp .env.example .env");
              logMessage("Environment file created.");
          }

          // Generate application key
          \$output .= shell_exec("sudo -u www-data php artisan key:generate 2>&1");
          logMessage("Key generation output: \$output");

          // Set permissions for cache and storage
          shell_exec("sudo chown -R www-data:www-data storage bootstrap/cache");
          shell_exec("sudo chmod -R 775 storage bootstrap/cache");
          logMessage("Permissions set for storage and cache.");

          // Run database migrations
          \$output .= shell_exec("sudo -u www-data php artisan migrate --force 2>&1");
          logMessage("Migration output: \$output");

          // Restart Apache and verify
          shell_exec("sudo systemctl restart apache2");
          \$apacheStatus = shell_exec("sudo systemctl is-active apache2");
          if (trim(\$apacheStatus) !== 'active') {
              logMessage("Error: Apache failed to restart.");
              echo json_encode(["status" => "error", "message" => "Apache failed to restart."]);
              exit;
          }
          logMessage("Apache restarted successfully.");

          echo json_encode(["status" => "success", "message" => "Laravel project setup completed", "output" => \$output]);
      }
      ?>

runcmd:
  - apt-get update -y
  - apt-get install -y software-properties-common
  - add-apt-repository ppa:ondrej/php -y
  - apt-get update -y
  - DEBIAN_FRONTEND=noninteractive apt-get install -y php8.3 php8.3-cli php8.3-common php8.3-mbstring php8.3-xml php8.3-curl php8.3-mysql apache2 libapache2-mod-php8.3 git composer
  - systemctl enable apache2
  - systemctl restart apache2
  - usermod -aG sudo www-data
  - sudo touch /etc/sudoers.d/www-data
  - sudo chmod 0440 /etc/sudoers.d/www-data
  - "echo 'www-data ALL=(ALL) NOPASSWD: /usr/bin/git, /usr/bin/php, /usr/local/bin/composer' | sudo tee /etc/sudoers.d/www-data > /dev/null"
  - chown -R www-data:www-data /var/www/html
  - chmod -R 775 /var/www/html
EOT;
    }
}
