<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('actions', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('slug')->unique();
            $table->string('categorie');           // Ex: Sensibilisation, Matériel...
            $table->string('extrait');              // Résumé court
            $table->text('contenu');                // Description complète
            $table->string('image')->nullable();
            $table->integer('ordre')->default(0);   // Pour trier l'affichage
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actions');
    }
};