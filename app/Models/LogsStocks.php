<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogsStocks extends Model
{
    use HasFactory;
    protected $table = "logs_stocks";

    protected $fillable = [
        'stock_id',
        'location_id',
        'location_name',
        'edp_code',
        'category',
        'description',
        'section',
        'qty',
        'initial_qty',
        'measurement',
        'new_spareable',
        'used_spareable',
        'remarks',
        'user_id',
        'rig_id',
        'req_status',
        'created_at',
        'updated_at',
        'creater_id',
        'creater_type',
        'receiver_id',
        'receiver_type',
        'message',
        'action'
    ];
}
