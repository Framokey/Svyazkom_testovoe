<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static first()
 */
class Resident extends Model
{
    use HasFactory;
    const CREATED_AT = 'start_date';
    const UPDATED_AT = null;
    protected $fillable = [
        'fio',
        'area',
    ];
}
