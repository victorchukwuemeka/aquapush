<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // Define which fields are mass assignable
    protected $fillable = [
        'user_id',
        'reference',
        'status',
        'raw_response',
    ];

    // Relationship: each payment belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
