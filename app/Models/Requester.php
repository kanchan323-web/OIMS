<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requester extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'available_qty',
        'requested_qty',
        'stock_id',
        'requester_id',
        'requester_rig_id',
        'supplier_id',
        'supplier_rig_id',
    ];
}
