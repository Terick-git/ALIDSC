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
        Schema::create('adherents', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->string('telephone');
            $table->string('ville')->nullable();
            $table->string('profession')->nullable();
            $table->date('date_adhesion');
            $table->integer('cotisation_montant')->default(5000);
            $table->enum('statut_cotisation', ['en_attente', 'a_jour', 'expire'])->default('en_attente');
            $table->date('date_dernier_paiement')->nullable();
            $table->enum('mode_paiement', ['mobile_money', 'especes', 'virement', 'autre'])->nullable();
            $table->text('notes')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adherents');
    }
};
