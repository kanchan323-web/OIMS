<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        "type",
        "notifiable_type",
        "notifiable_id",
        "data",
        "user_id",
        "read_at",
        "is_admin_read",
        "rig_id"
    ];


    public function users()
    {
        return $this->belongsToMany(User::class, 'notification_user', 'notification_id', 'user_id')
            ->withTimestamps();
    }
}
