<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('actualites', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('slug')->unique();
            $table->string('extrait');          // Résumé court pour les cartes
            $table->text('contenu');            // Corps de l'article
            $table->string('image')->nullable(); // Chemin de l'image
            $table->boolean('publie')->default(false);
            $table->timestamp('publie_le')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Auteur
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actualites');
    }
};
