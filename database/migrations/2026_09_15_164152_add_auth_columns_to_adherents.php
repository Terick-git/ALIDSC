<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('adherents', function (Blueprint $table) {
            $table->string('password')->nullable()->after('email');
            $table->rememberToken()->after('password');
            $table->timestamp('email_verified_at')->nullable()->after('remember_token');
            $table->timestamp('derniere_connexion')->nullable()->after('email_verified_at');
            $table->boolean('espace_actif')->default(false)->after('derniere_connexion');
        });
    }

    public function down(): void
    {
        Schema::table('adherents', function (Blueprint $table) {
            $table->dropColumn([
                'password', 'remember_token', 'email_verified_at',
                'derniere_connexion', 'espace_actif',
            ]);
        });
    }
};
