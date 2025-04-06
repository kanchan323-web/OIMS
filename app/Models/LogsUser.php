<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogsUser extends Model
{
    use HasFactory;
    protected $table = 'logs_users';
    protected $fillable = [
        'user_name',
        'cpf_no',
        'email',
        'password',
        'user_status',
        'user_type',
        'rig_id',
        'creater_id',
        'creater_type',
        'remember_token',
        'receiver_type',
        'message'
    ];
}
