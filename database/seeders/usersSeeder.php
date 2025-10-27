<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class usersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        User::create(
            [
                'name' => 'Admin',
                'email' => 'admin@email.com',
                'password' => Hash::make('admin'),
                'role' => 1,
                'warehouseID' => 1,
                'lang' => 'en',
            ],

        );
        User::create(
            [
                'name' => 'Operator',
                'email' => 'operator@email.com',
                'password' => Hash::make('operator'),
                'role' => 2,
                'warehouseID' => 2,
                'lang' => 'en',
            ],
        );
        User::create(
            [
                'name' => 'Store Keeper',
                'email' => 'store@email.com',
                'password' => Hash::make('store'),
                'role' => 3,
                'warehouseID' => 1,
                'lang' => 'en',
            ],
        );
    }
}
