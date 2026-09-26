<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ressources', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('fichier');                    // PDF, DOC, etc.
            $table->string('categorie');                  // etude, publication, rapport, journal, educative
            $table->string('auteur')->nullable();
            $table->year('annee')->nullable();
            $table->string('couverture')->nullable();
            $table->integer('telechargements')->default(0);
            $table->boolean('public')->default(true);     // true = visible publiquement
            $table->integer('ordre')->default(0);
            $table->boolean('actif')->default(true);
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ressources');
    }
};