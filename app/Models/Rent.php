<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rent extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'amount_due',
        'amount_received',
        'amount_remaining',
        'date',
        'status',
        'comment',
        'bill_id',
        'due_date',
    ];

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function adjustments()
    {
        return $this->hasMany(Adjustment::class);
    }
}

