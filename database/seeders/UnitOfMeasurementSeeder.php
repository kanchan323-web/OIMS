<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitOfMeasurementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('unit_of_measurement_master')->insert([
            ['unit_name' => 'Each', 'type_of_unit' => 'integer', 'abbreviation' => 'EA'],
            ['unit_name' => 'Foot', 'type_of_unit' => 'decimal', 'abbreviation' => 'FT'],
            ['unit_name' => 'Gallon', 'type_of_unit' => 'decimal', 'abbreviation' => 'GAL'],
            ['unit_name' => 'Kilogram', 'type_of_unit' => 'decimal', 'abbreviation' => 'KG'],
            ['unit_name' => 'Kit', 'type_of_unit' => 'integer', 'abbreviation' => 'KIT'],
            ['unit_name' => 'Kiloliter', 'type_of_unit' => 'decimal', 'abbreviation' => 'KL'],
            ['unit_name' => 'Liter', 'type_of_unit' => 'decimal', 'abbreviation' => 'L'],
            ['unit_name' => 'Pound', 'type_of_unit' => 'decimal', 'abbreviation' => 'LB'],
            ['unit_name' => 'Meter', 'type_of_unit' => 'decimal', 'abbreviation' => 'M'],
            ['unit_name' => 'Cubic Meter', 'type_of_unit' => 'decimal', 'abbreviation' => 'M3'],
            ['unit_name' => 'Metric Ton', 'type_of_unit' => 'decimal', 'abbreviation' => 'MT'],
            ['unit_name' => 'Number', 'type_of_unit' => 'decimal', 'abbreviation' => 'NO'],
            ['unit_name' => 'Pallet', 'type_of_unit' => 'integer', 'abbreviation' => 'PAA'],
            ['unit_name' => 'Pack', 'type_of_unit' => 'integer', 'abbreviation' => 'PAC'],
            ['unit_name' => 'Roll', 'type_of_unit' => 'integer', 'abbreviation' => 'ROL'],
            ['unit_name' => 'Set', 'type_of_unit' => 'integer', 'abbreviation' => 'ST'],
        ]);
    }
}
