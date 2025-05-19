<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CustomUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $admin =  [ 
            'role_id' => 1,
            'name' => 'Admin One',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('publish'),
            'confirm_password' => Hash::make('publish'),
            'created_at' => $now,
            'updated_at' => $now
        ];

        CustomUser::insert($admin);        
    }
}
