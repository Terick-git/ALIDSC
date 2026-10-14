<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('adherent_paiements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('adherent_id')
                ->constrained('adherents')
                ->cascadeOnDelete();

            // Référence unique (COT-20261003-A1B2C3)
            $table->string('reference')->unique();

            // Montant en FCFA
            $table->unsignedInteger('montant');

            // Mode de paiement
            $table->enum('mode_paiement', [
                'mobile_money', 'especes', 'virement', 'autre'
            ])->default('mobile_money');

            // Statut
            $table->enum('statut', [
                'en_attente', 'valide', 'annule'
            ])->default('en_attente');

            // Traçabilité
            $table->timestamp('paid_at')->nullable();
            $table->string('receipt_path')->nullable();
            $table->text('notes')->nullable();

            // Qui a enregistré le paiement (admin)
            $table->foreignId('enregistre_par')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['adherent_id', 'statut']);
            $table->index('paid_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adherent_paiements');
    }
};
