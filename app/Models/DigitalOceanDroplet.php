<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DigitalOceanDroplet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'api_token',
        'droplet_size',
        'droplet_name',
        'image',
        'repository',
        'region',
        'status',
    ];

}
