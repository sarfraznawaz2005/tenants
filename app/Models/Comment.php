<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'rent_id',
        'body',
    ];

    public function rent()
    {
        return $this->belongsTo(Rent::class);
    }
}
