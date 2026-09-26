<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ⚠️ Compte admin — créé UNIQUEMENT si les variables d'env sont définies
        $email    = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (!$email || !$password) {
            $this->command->warn('ADMIN_EMAIL ou ADMIN_PASSWORD manquant. Seeder ignoré.');
            return;
        }

        if (User::where('email', $email)->exists()) {
            $this->command->info("Admin {$email} existe déjà. Seeder ignoré.");
            return;
        }

        User::factory()->create([
            'name'     => env('ADMIN_NAME', 'Admin ALIDSC'),
            'email'    => $email,
            'password' => bcrypt($password),
            'role'     => 'admin',
        ]);

        $this->command->info("Admin {$email} créé.");
    }
}
