<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('temoignages', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('role'); // Ex: "Élève au Lycée de Maroua", "Parent d'élève"
            $table->string('photo')->nullable();
            $table->text('citation'); // Le témoignage
            $table->enum('categorie', ['eleve', 'parent', 'membre', 'partenaire'])->default('eleve');
            $table->integer('ordre')->default(0);
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('temoignages');
    }
};