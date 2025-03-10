<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get super admin email from environment variables or use default
        $superAdminEmail = env('SUPER_ADMIN_EMAIL', 'superadmin@example.com');

        // Check if super admin already exists to prevent duplicates
        if (!User::where('email', $superAdminEmail)->exists()) {
            User::create([
                'name' => env('SUPER_ADMIN_NAME', 'Super Admin'),
                'email' => $superAdminEmail,
                'email_verified_at' => now(),
                'password' => Hash::make(env('SUPER_ADMIN_PASSWORD', 'superadmin123')),
                'remember_token' => Str::random(10),
                'is_super_admin' => true
            ]);

            $this->command->info('Super Admin user created successfully.');
        } else {
            $this->command->info('Super Admin user already exists.');
        }
    }
}
