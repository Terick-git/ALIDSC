<?php

namespace App\Console\Commands;

use App\Models\Adherent;
use App\Notifications\RappelCotisation as RappelCotisationNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class RappelCotisation extends Command
{
    protected $signature = 'alidsc:rappel-cotisation
                            {--dry-run : Affiche les adhérents concernés sans envoyer d\'email}
                            {--jours=30 : Nombre de jours avant expiration pour déclencher le rappel}';

    protected $description = 'Envoie un rappel de cotisation aux adhérents dont la cotisation expire bientôt';

    public function handle(): int
    {
        $jours  = (int) $this->option('jours');
        $dryRun = (bool) $this->option('dry-run');

        $this->info("Recherche des adhérents dont la cotisation expire dans {$jours} jours...");

        // Fenêtre : cotisation dont l'expiration est dans [today, today + $jours]
        // ET qui n'a pas déjà reçu un rappel pour cette période.
        $adherents = Adherent::actifs()
            ->whereNotNull('date_dernier_paiement')
            ->whereNotNull('statut_cotisation')
            ->whereIn('statut_cotisation', ['a_jour', 'expire'])
            ->get()
            ->filter(function (Adherent $adherent) use ($jours) {
                $joursRestants = $adherent->jours_avant_expiration;

                if ($joursRestants === null || $joursRestants > $jours || $joursRestants < 0) {
                    return false;
                }

                // Déjà envoyé récemment ? On évite de spammer.
                // On considère "récent" = dans les $jours derniers jours.
                if ($adherent->rappel_cotisation_envoye_le) {
                    $joursDepuisDernierRappel = $adherent->rappel_cotisation_envoye_le->diffInDays(now());
                    if ($joursDepuisDernierRappel < $jours) {
                        return false;
                    }
                }

                return true;
            });

        if ($adherents->isEmpty()) {
            $this->info('Aucun adhérent concerné.');
            return self::SUCCESS;
        }

        $this->info($adherents->count() . ' adhérent(s) concerné(s).');

        if ($dryRun) {
            $this->table(
                ['ID', 'Nom', 'Email', 'Jours restants'],
                $adherents->map(fn (Adherent $a) => [
                    $a->id,
                    $a->nom_complet,
                    $a->email,
                    $a->jours_avant_expiration,
                ])
            );

            $this->warn('Mode --dry-run : aucun email envoyé.');
            return self::SUCCESS;
        }

        $envoyes = 0;
        $echecs  = 0;

        foreach ($adherents as $adherent) {
            try {
                $adherent->notify(new RappelCotisationNotification($adherent, $adherent->jours_avant_expiration));
                $adherent->update(['rappel_cotisation_envoye_le' => now()]);
                $envoyes++;
            } catch (\Throwable $e) {
                $echecs++;
                Log::error('Envoi rappel cotisation échoué', [
                    'adherent_id' => $adherent->id,
                    'exception'   => $e->getMessage(),
                ]);
            }
        }

        $this->info("✅ {$envoyes} rappel(s) envoyé(s).");

        if ($echecs > 0) {
            $this->warn("⚠️ {$echecs} échec(s) — voir storage/logs/laravel.log.");
        }

        return self::SUCCESS;
    }
}