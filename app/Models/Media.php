<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Media extends Model
{
    use HasFactory;
    protected $table = "media";
    protected $fillable = ['url', 'public_id'];

    // Define el cast para 'url' como un array
    protected $casts = [
        'url' => 'array',
    ];
 
}
