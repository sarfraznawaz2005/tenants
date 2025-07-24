<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'cnic',
        'address',
        'lease_date',
        'monthly_rent',
    ];

    public function rents()
    {
        return $this->hasMany(Rent::class);
    }
}

