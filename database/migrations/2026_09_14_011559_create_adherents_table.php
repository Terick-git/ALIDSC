<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('adherents', function (Blueprint $table) {
            $table->id();

            // ─── Identité ───────────────────────────────
            $table->string('nom');
            $table->string('prenom');
            $table->date('date_naissance')->nullable();

            // ─── Auth (ancien add_auth_columns) ─────────
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('derniere_connexion')->nullable();
            $table->boolean('espace_actif')->default(false);

            // ─── Contact ────────────────────────────────
            $table->string('telephone');
            $table->string('ville')->nullable();
            $table->string('adresse')->nullable();
            $table->string('etablissement')->nullable();

            // ─── Catégorie & cotisation (workflow) ──────
            $table->enum('categorie', ['simple', 'actif'])->default('simple');
            $table->integer('cotisation_proposee')->nullable();
            $table->date('date_adhesion');
            $table->integer('cotisation_montant')->default(5000);

            // ─── Statuts ────────────────────────────────
            $table->enum('statut_cotisation', ['en_attente', 'a_jour', 'expire'])
                ->default('en_attente');

            $table->enum('statut_demande', [
                'brouillon',
                'en_attente',
                'acceptee',
                'refuse',
                'paiement_en_attente',
                'paiement_validate',
                'active',
                'expiree',
            ])->default('en_attente');

            $table->text('motif_refus')->nullable();
            $table->string('piece_identite')->nullable();

            // ─── Paiement ───────────────────────────────
            $table->date('date_dernier_paiement')->nullable();
            $table->enum('mode_paiement', ['mobile_money', 'especes', 'virement', 'autre'])
                ->nullable();

            // ─── Divers ─────────────────────────────────
            $table->text('notes')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();

            // NB : la colonne `profession` a été supprimée définitivement
            // (doublon avec `etablissement`, cf. ancien drop_profession).
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adherents');
    }
};