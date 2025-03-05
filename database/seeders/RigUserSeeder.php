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
                [
                    'name' => 'Sagar Bhushan',
                ],
                [
                    'name' => 'Sagar Vijay',
                ],
                [
                    'name' => 'Sagar Gaurav',
                ],
                [
                    'name' => 'Sagar Shakti',
                ],
                [
                    'name' => 'Sagar Kiran',
                ],
                [
                    'name' => 'Sagar Uday',
                ],
                [
                    'name' => 'Sagar Jyoti',
                ],
                [
                    'name' => 'Sagar Ratna',
                ],
                [
                    'name' => 'Nhava',
                ],
                [
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
