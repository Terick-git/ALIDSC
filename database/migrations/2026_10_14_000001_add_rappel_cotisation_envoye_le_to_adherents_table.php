<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('adherents', function (Blueprint $table) {
            // Date à laquelle on a envoyé le dernier rappel de cotisation.
            // Évite les doublons si la commande tourne plusieurs fois.
            $table->timestamp('rappel_cotisation_envoye_le')
                ->nullable()
                ->after('date_dernier_paiement');
        });
    }

    public function down(): void
    {
        Schema::table('adherents', function (Blueprint $table) {
            $table->dropColumn('rappel_cotisation_envoye_le');
        });
    }
};