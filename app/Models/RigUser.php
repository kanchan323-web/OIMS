<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RigUser extends Model
{
    use HasFactory;

    protected $table = 'rig_users';

    protected $fillable = ['name','location_id'];

    
    public function users()
    {
        return $this->hasMany(User::class, 'rig_id', 'id');
    }

    
}
