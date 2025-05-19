<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use Carbon\Carbon;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $role = 
            [
                [ 'role' => 'Superadmin', 'created_at' => $now, 'updated_at' => $now],
                [ 'role' => 'Admin', 'created_at' => $now, 'updated_at' => $now],
                [ 'role' => 'User', 'created_at' => $now, 'updated_at' => $now]
            ];

        Role::insert($role);        
    }
}
