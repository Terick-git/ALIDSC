<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();

            // Référence unique générée par nous (utilisée par CinetPay)
            $table->string('reference')->unique();

            // Donateur
            $table->string('nom');
            $table->string('email');
            $table->string('telephone', 30);

            // Montant
            $table->unsignedInteger('montant');
            $table->string('devise', 3)->default('XOF');

            // Moyen de paiement choisi
            $table->enum('moyen', ['orange', 'mtn', 'carte', 'wave', 'autre'])
                ->default('orange');

            // Statut global du don
            $table->enum('statut', ['en_attente', 'paye', 'echoue', 'annule'])
                ->default('en_attente');

            // Références CinetPay
            $table->string('cinetpay_transaction_id')->nullable()->index();
            $table->string('cinetpay_payment_token')->nullable();
            $table->string('cinetpay_payment_url', 500)->nullable();
            $table->json('cinetpay_response')->nullable();

            // Suivi
            $table->timestamp('paid_at')->nullable();
            $table->string('receipt_path')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['statut', 'created_at']);
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};