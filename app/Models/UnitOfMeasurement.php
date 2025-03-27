<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitOfMeasurement extends Model
{
    use HasFactory;
    protected $table = 'unit_of_measurement_master'; // Define table name
    protected $primaryKey = 'id'; // Define primary key
    public $timestamps = false; // Disable timestamps if not needed

    protected $fillable = ['unit_name', 'type_of_unit', 'abbreviation'];
}
