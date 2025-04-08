<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogsRequesters extends Model
{
    protected $table = 'logs_requesters';

    protected $fillable = [
        'request_id',
        'status',
        'RID',
        'available_qty',
        'requested_qty',
        'stock_id',
        'requester_stock_id',
        'requester_id',
        'requester_rig_id',
        'supplier_id',
        'supplier_rig_id',
        'created_at',
        'updated_at',
        'creater_id',
        'creater_type',
        'receiver_id',
        'receiver_type',
        'message',
        'action',
    ];
}
