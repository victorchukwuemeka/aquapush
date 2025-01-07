<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;
use App\Models\DigitalOceanDroplet;
use phpseclib3\Net\SSH2;

class DigitalOceanController extends Controller
{
    public function show($id){

        // Fetch the Droplet by ID
        $droplet = DigitalOceanDroplet::findOrFail($id);

        // Pass the Droplet data to the view
        return view('dashboard.droplets.show-droplet', compact('droplet'));
    }

    public function setupProject(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'github_repo' => 'required|string',
            'php_version' => 'required|string',
            'db_name' => 'required|string',
            'db_user' => 'required|string',
            'db_password' => 'required|string',
        ]);

        // Fetch the Droplet by ID
        $droplet = DigitalOceanDroplet::findOrFail($id);
        
        // Connect to the Droplet via SSH
        $ssh = new SSH2($droplet->ip_address);
        if (!$ssh->login($droplet->ssh_username, $droplet->ssh_private_key)) {
            return redirect()->back()->with('error', 'SSH login failed. Please check your credentials.');
        }

        // Step 1: Install PHP
        $ssh->exec("sudo apt-get update");
        $ssh->exec("sudo apt-get install -y php{$request->input('php_version')}");

        // Step 2: Install MariaDB
        $ssh->exec("sudo apt-get install -y mariadb-server");
        $ssh->exec("sudo mysql -e \"CREATE DATABASE {$request->input('db_name')};\"");
        $ssh->exec("sudo mysql -e \"CREATE USER '{$request->input('db_user')}'@'localhost' IDENTIFIED BY '{$request->input('db_password')}';\"");
        $ssh->exec("sudo mysql -e \"GRANT ALL PRIVILEGES ON {$request->input('db_name')}.* TO '{$request->input('db_user')}'@'localhost';\"");
        $ssh->exec("sudo mysql -e \"FLUSH PRIVILEGES;\"");

        // Step 3: Clone GitHub Repository
        $ssh->exec("git clone {$request->input('github_repo')} /var/www/laravel");

        // Step 4: Set Up Web Server (e.g., Nginx or Apache)
        $ssh->exec("sudo apt-get install -y nginx");
        $ssh->exec("sudo cp /var/www/laravel/nginx.conf /etc/nginx/sites-available/laravel");
        $ssh->exec("sudo ln -s /etc/nginx/sites-available/laravel /etc/nginx/sites-enabled/");
        $ssh->exec("sudo systemctl restart nginx");

        // Step 5: Set Up Environment Variables
        $ssh->exec("echo 'DB_DATABASE={$request->input('db_name')}' >> /var/www/laravel/.env");
        $ssh->exec("echo 'DB_USERNAME={$request->input('db_user')}' >> /var/www/laravel/.env");
        $ssh->exec("echo 'DB_PASSWORD={$request->input('db_password')}' >> /var/www/laravel/.env");

        // Step 6: Run Composer and Migrations
        $ssh->exec("cd /var/www/laravel && composer install --no-dev --optimize-autoloader");
        $ssh->exec("cd /var/www/laravel && php artisan migrate --force");

        // Return success message
        return redirect()->back()->with('success', 'Project setup completed successfully!');
    }
    
}
