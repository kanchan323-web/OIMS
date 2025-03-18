<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MasterStatus;
use Carbon\Carbon;

class MasterStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $masterStatus = collect(
            [
                [
                    'status_name' => 'Pendding',
                ],
                [
                    'status_name' => 'Query',
                ],
                [
                    'status_name' => 'Received',
                ],
                [
                    'status_name' => 'Approve',
                ],
                [
                    'status_name' => 'Decline',
                ],
                ]);

                $masterStatus->each(function($masterStatu){
                    MasterStatus::create($masterStatu);
                });
    }
}
