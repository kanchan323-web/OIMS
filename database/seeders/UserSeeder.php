<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\user;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users=collect(
        [
            [
            'name' => 'kanchan',
            'email' => 'kanchan123@gmail.com',
            'password' => Hash::make('kanchan123'),
            'role' => 'admin',
            'rig_id' => '1',
            ],
            [
                'name' => 'vijay',
            'email' => 'vijay123@gmail.com',
            'password' => Hash::make('vijay123'),
            'role' => 'user',
            'rig_id' => '1',
            ],
            [
                'name' => 'anu',
            'email' => 'anu123@gmail.com',
            'password' => Hash::make('anu123'),
            'role' => 'user',
            'rig_id' => '1',
            ],
            [
                'name' => 'shiv',
            'email' => 'shiv123@gmail.com',
            'password' => Hash::make('shiv123'),
            'role' => 'user',
            'rig_id' => '1',
            ],
            [
                'name' => 'kusum',
            'email' => 'kusum123@gmail.com',
            'password' => Hash::make('kusm123'),
            'role' => 'user',
            'rig_id' => '1',

            ],
            [
                'name' => 'shubham',
            'email' => 'shubham123@gmail.com',
            'password' => Hash::make('shubham123'),
            'role' => 'user',
            'rig_id' => '2',
            ]
        ]);



        $users->each(function($user){
            user::create($user);
        });

   /*     user::create([
            'email' => 'shubham123@gmail.com',
            'password' => Hash::make('shubham123'),
            'role' => 'rig_user',
            'rig_id' => '2'
        ]);
   */
    }
}
