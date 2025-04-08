<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogsRequestStatus extends Model
{
    use HasFactory;
    protected $table = 'logs_request_status';

    protected $fillable = [
        'decline_msg',
        'query_msg',
        'supplier_qty',
        'supplier_new_spareable',
        'supplier_used_spareable',
        'request_id',
        'status_id',
        'user_id',
        'rig_id',
        'sent_to',
        'sent_from',
        'created_at',
        'updated_at',
        'creater_id',
        'creater_type',
        'receiver_id',
        'receiver_type',
        'message',
    ];
}
