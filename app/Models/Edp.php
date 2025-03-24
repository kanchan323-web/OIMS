<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Edp extends Model
{
    use HasFactory;

    protected $fillable = [
        'edp_code',
        'category',
        'description',
        'section',
        'measurement',
    ];
}
