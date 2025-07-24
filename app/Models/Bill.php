<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = ['bill_type_id', 'date', 'amount', 'picture'];

    public function billType()
    {
        return $this->belongsTo(BillType::class);
    }
}