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
            'user_name' => 'admin',
            'cpf_no'    => '12345',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
            'role' => 'admin',
            'rig_id' => '1',
            ],
            [
            'user_name' => '105914',
            'cpf_no'    => '105914',
            'email' => 'test@gmail.com',
            'password' => Hash::make('test123'),
            'role' => 'user',
            'rig_id' => '2',
            ],
            [
            'user_name' => '105927',
            'cpf_no'    => '105927',
            'email' => 'test2@gmail.com',
            'password' => Hash::make('test123'),
            'role' => 'user',
            'rig_id' => '2',
            ],
            [
            'user_name' => '121312',
            'cpf_no'    => '121312',
            'email' => 'test3@gmail.com',
            'password' => Hash::make('test123'),
            'role' => 'user',
            'rig_id' => '3',
            ],
            [
            'user_name' => '124991',
            'cpf_no'    => '124991',
            'email' => 'test4@gmail.com',
            'password' => Hash::make('test123'),
            'role' => 'user',
            'rig_id' => '3',

            ],
            [
            'user_name' => '135641',
            'cpf_no'    => '135641',
            'email' => 'test5@gmail.com',
            'password' => Hash::make('test123'),
            'role' => 'user',
            'rig_id' => '4',
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
