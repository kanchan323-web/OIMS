<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogRequester extends Model
{
    use HasFactory;

    public $table = 'logs_requesters';

    public $fillable = [
        'request_id',
        'RID',
        'dn_no',
        'status',
        'available_qty',
        'requested_qty',
        'stock_id',
        'requester_id',
        'requester_rig_id',
        'supplier_id',
        'supplier_rig_id',
        'remarks',
        'creator_id',
        'creator_type',
        'receiver_id',
        'receiver_type',
        'message',
        'action'
    ];

    public function requestStatuses()
    {
        return $this->hasMany(RequestStatus::class, 'request_id', 'id');
    }

    public function requestedStock()
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }

    public function requesterStock()
    {
        return $this->belongsTo(Stock::class, 'requester_stock_id');
    }

    public function latestStatus()
    {
        return $this->hasOne(RequestStatus::class, 'request_id')->latestOfMany();
    }
}
