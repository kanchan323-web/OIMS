<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RigUser;
use Carbon\Carbon;

class RigUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $rigUsers = collect(
            [
           /*     [
                    'location_id' => 'admin',
                    'name' => 'admin',
                ],
            */
                [
                    'location_id' => 'RP06',
                    'name' => 'Sagar Bhushan',
                ],
                [
                    'location_id' => 'RP03',
                    'name' => 'Sagar Vijay',
                ],
                [
                    'location_id' => 'RP04',
                    'name' => 'Sagar Gaurav',
                ],
                [
                    'location_id' => 'RP08',
                    'name' => 'Sagar Shakti',
                ],
                [
                    'location_id' => 'RN02',
                    'name' => 'Sagar Kiran',
                ],
                [
                    'location_id' => 'RN09',
                    'name' => 'Sagar Uday',
                ],
                [
                    'location_id' => 'RN010',
                    'name' => 'Sagar Jyoti',
                ],
                [
                    'location_id' => 'RN05',
                    'name' => 'Sagar Ratna',
                ],
                [
                    'location_id' => 'RN07',
                    'name' => 'Nhava',
                ],
                [
                    'location_id' => 'RN01',
                    'name' => 'Pipapav',
                ],
                ]);

                $rigUsers->each(function($rigUser){
                    RigUser::create($rigUser);
                });

             /*   RigUser::create([
                    'name' => 'navi mumbai',
                ]);
                */
    }
}
