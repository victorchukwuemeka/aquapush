<?php 

namespace App\Services;

class CloudInitService
{
    public function generateCloudInitScript()
    {
        return <<<EOT
#cloud-config
runcmd:
  - apt-get update -y
  - apt-get install -y php-cli apache2 libapache2-mod-php
  - |
    echo "<?php
    \$API_KEY = 'YOUR_SECRET_KEY';
    \$headers = getallheaders();
    if (!isset(\$headers['Authorization']) || \$headers['Authorization'] !== 'Bearer ' . \$API_KEY) {
        http_response_code(403);
        echo json_encode([\"status\" => \"error\", \"message\" => \"Unauthorized\"]);
        exit;
    }
    if (\$_SERVER['REQUEST_METHOD'] === 'POST') {
        \$data = json_decode(file_get_contents('php://input'), true);
        \$package = escapeshellarg(\$data['package']);
        \$output = shell_exec(\"sudo apt-get install -y \$package 2>&1\");
        echo json_encode([\"status\" => \"success\", \"output\" => \$output]);
    }
    ?>" > /var/www/html/install.php
  - systemctl restart apache2
EOT;
    }
}
