<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evenements', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('slug')->unique();
            $table->string('extrait');                  // Résumé court
            $table->text('contenu');                    // Description complète
            $table->string('image')->nullable();
            $table->dateTime('date_debut');
            $table->dateTime('date_fin')->nullable();
            $table->string('lieu');
            $table->string('ville')->nullable();
            $table->string('intervenants')->nullable(); // Noms séparés par virgule
            $table->boolean('inscription_requise')->default(false);
            $table->string('lien_inscription')->nullable();
            $table->enum('statut', ['brouillon', 'publie', 'annule'])->default('brouillon');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evenements');
    }
};