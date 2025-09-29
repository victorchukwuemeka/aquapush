<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (json_last_error() !== JSON_ERROR_NONE || empty($data['repo'])) {
        http_response_code(400);
        logMessage("Invalid request data");
        die(json_encode(["status" => "error", "message" => "Invalid request"]));
    }

    $repo = escapeshellarg($data['repo']);
    $dbName = escapeshellarg($data['db_name'] ?? 'aquapush');
    $dbUser = escapeshellarg($data['db_user'] ?? 'aquapush_user');
    $dbPass = escapeshellarg($data['db_pass'] ?? 'another_strong_password');

    $projectDir = '/var/www/html/repo';

    try {
        // --- DB creation dynamically ---
        $rootPass = 'your_strong_password'; // root password set in cloud-init
        $createDbCmd = "mysql -u root -p{$rootPass} -e \"CREATE DATABASE IF NOT EXISTS {$data['db_name']};\"";
        exec($createDbCmd, $output, $returnCode);
        if ($returnCode !== 0) {
            throw new \RuntimeException("DB creation failed: " . implode("\n", $output));
        }

        $grantCmd = "mysql -u root -p{$rootPass} -e \"GRANT ALL ON {$data['db_name']}.* TO '{$data['db_user']}'@'localhost' IDENTIFIED BY '{$data['db_pass']}'; FLUSH PRIVILEGES;\"";
        exec($grantCmd, $output, $returnCode);
        if ($returnCode !== 0) {
            throw new \RuntimeException("Grant failed: " . implode("\n", $output));
        }

        // --- Setup .env dynamically ---
        setupProject($projectDir, $data['db_name'], $data['db_user'], $data['db_pass']);

        echo json_encode(["status" => "success", "message" => "Setup completed"]);
    } catch (\Exception $e) {
        http_response_code(500);
        logMessage("Error: " . $e->getMessage());
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
}
