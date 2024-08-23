<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['customer', 'merchant'];

        foreach ($roles as $role) {
            DB::table('roles')->insert([
                'id' => \Ramsey\Uuid\Uuid::uuid4(),
                'role' => $role,
                'created_at' => now(),
            ]);
        }
    }
}
