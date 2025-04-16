<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogsSection extends Model
{
    use HasFactory;
    protected $table = 'logs_sections';

    protected $fillable = [
        'section_id',
        'section_name',
        'created_at',
        'updated_at',
        'creater_id',
        'creater_type',
        'message'
    ];
}
