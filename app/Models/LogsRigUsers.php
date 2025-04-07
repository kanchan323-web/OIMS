<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogsRigUsers extends Model
{
    use HasFactory;
    protected $table = 'logs_rig_users';
    
    protected $fillable = [
        'location_id', 
        'name', 
        'created_at', 
        'updated_at', 
        'creater_id', 
        'creater_type', 
        'receiver_id', 
        'receiver_type', 
        'message'
    ];
}
