<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $email    = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (! $email || ! $password) {
            $this->command->warn('ADMIN_EMAIL ou ADMIN_PASSWORD manquant. Seeder ignoré.');
            return;
        }

        if (User::where('email', $email)->exists()) {
            $this->command->info("Admin {$email} existe déjà. Seeder ignoré.");
            return;
        }

        $user = new User();
        $user->forceFill([
            'name'              => env('ADMIN_NAME', 'Admin ALIDSC'),
            'email'             => $email,
            'password'          => Hash::make($password),
            'role'              => 'admin',
            'email_verified_at' => now(),
        ])->save();

        $this->command->info("Admin {$email} créé.");
    }
}