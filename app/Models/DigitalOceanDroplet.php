<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DigitalOceanDroplet extends Model
{
    use HasFactory;
    
    protected  $table = 'digital_ocean_droplet';

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

    public function get_id(){
        return $this->attributes['status'];
    }
  
    public function set_id($status){
        $this->attributes['status'] = $status;
    }

}
