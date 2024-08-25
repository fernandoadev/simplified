<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'id' => 'dd22e8cd-8b15-495b-a3dc-9ebdeadebf59',
                'type' => 'customer',
                'name' => 'john doe',
                'document' => '89750082079',
                'email' => 'john.doe@example.com',
                'password' => Hash::make('password456'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
                'created_at' => now(),
            ],
            [
                'id' => 'c3726ccb-7de7-4424-ac86-d9498dd8163c',
                'type' => 'merchant',
                'name' => 'test ltda',
                'document' => Hash::make('password456'),
                'email' => 'jane.smith@example.com',
                'password' => 'password456',
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
                'created_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert($user);

            DB::table('wallet')->insert([
                'id' => \Ramsey\Uuid\Uuid::uuid4(),
                'user_id' => $user['id'],
                'name' => $user['name'] . ' wallet',
                'balance' => 5000,
                'created_at' => now(),
            ]);
        }
    }
}
