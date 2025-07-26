<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adjustment extends Model
{
    use HasFactory;

    protected $fillable = ['rent_id', 'name', 'type', 'amount'];

    public function rent()
    {
        return $this->belongsTo(Rent::class);
    }
}