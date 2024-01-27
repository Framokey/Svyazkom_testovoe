<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    use HasFactory;

    const CREATED_AT = 'begin_date';

    protected $fillable = [
        'end_date'
    ];
}
