<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('adherents', function (Blueprint $table) {
            $table->enum('categorie', ['simple', 'actif'])->default('simple')->after('profession');
            // Montant proposé par l'adhérent (nullable = l'admin fixera)
            $table->integer('cotisation_proposee')->nullable()->after('categorie');
            // Workflow de demande (§13.5)
            $table->enum('statut_demande', [
                'brouillon',
                'en_attente',
                'acceptee',
                'refuse',
                'paiement_en_attente',
                'paiement_validate',
                'active',
                'expiree',
            ])->default('en_attente')->after('statut_cotisation');

            // Motif de refus
            $table->text('motif_refus')->nullable()->after('statut_demande');

            // Pièce d'identité (§13.4)
            $table->string('piece_identite')->nullable()->after('motif_refus');

            // Champs du formulaire §13.4
            $table->date('date_naissance')->nullable()->after('prenom');
            $table->string('adresse')->nullable()->after('ville');
            $table->string('etablissement')->nullable()->after('profession');
        });
    }

    public function down(): void
    {
        Schema::table('adherents', function (Blueprint $table) {
            $table->dropColumn([
                'categorie', 'statut_demande', 'motif_refus', 'piece_identite',
                'date_naissance', 'adresse', 'etablissement',
            ]);
        });
    }
};
