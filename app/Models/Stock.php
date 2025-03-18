<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id',
        'location_name',
        'edp_code',
        'category',
        'description',
        'section',
        'qty',
        'measurement',
        'new_spareable',
        'used_spareable',
        'remarks',
        'user_id',
        'rig_id',
    ];
}
