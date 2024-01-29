<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    const CREATED_AT = null;
    const UPDATED_AT = null;


    protected $fillable = [
        'resident_id',
        'period_id',
        'amount_rub'
    ];
}
