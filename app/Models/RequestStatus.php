<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestStatus extends Model
{
    use HasFactory;

    protected $table = 'request_status';

    protected $fillable = [
        'request_id',  
        'status_id',
        'decline_msg',
        'query_msg',
        'supplier_qty',
        'supplier_new_spareable', 
        'supplier_used_spareable', 
        'user_id',
        'rig_id',
        'is_read',
    ];

    public function requestStatus()
    {
        return $this->hasOne(RequestStatus::class, 'request_id', 'id');
    }

}
