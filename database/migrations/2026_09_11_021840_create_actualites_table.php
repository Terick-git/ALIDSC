<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('actualites', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('slug')->unique();
            $table->string('extrait');
            $table->text('contenu');
            $table->string('image')->nullable();
            $table->boolean('publie')->default(false);
            $table->boolean('interne')->default(false);   // ancien add_interne
            $table->timestamp('publie_le')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actualites');
    }
};