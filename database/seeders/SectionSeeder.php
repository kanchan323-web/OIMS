<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Section;
use Carbon\Carbon;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = collect(
            [
                [
                    'section_name' => 'WELL',
                ],
                [
                    'section_name' => 'HSD',
                ],
                [
                    'section_name' => 'ENGG',
                ],
                [
                    'section_name' => 'DRILL',
                ],
                [
                    'section_name' => 'CMTG',
                ],
                [
                    'section_name' => 'CHEM',
                ],
            ]);

                $sections->each(function($section){
                    Section::create($section);
                });
    }
}
