<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogsEdps extends Model
{
    use HasFactory;
    protected $table = 'logs_edps';
    protected $fillable = [
        'edp_code',
        'category',
        'material_group',
        'description',
        'section',
        'measurement',
        'creater_id',
        'creater_type',
        'receiver_id',
        'receiver_type',
        'message'
    ];
}
