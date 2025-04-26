<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
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
        'expected_date',
    ];

    public function getFormattedDateAttribute(){
        return $this->created_at->format('d-m-Y');
    }
}
