<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogsCategory extends Model
{
    use HasFactory;

    protected $table = 'logs_categories'; 

    protected $fillable = [
        'category_name',
        'creater_id',
        'creater_type',
        'receiver_id',
        'receiver_type',
        'message',
    ];

    public $timestamps = true; 
}
