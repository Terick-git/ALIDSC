<?php

namespace App\Notifications;

use App\Models\Adherent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RappelCotisation extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Adherent $adherent,
        public int $joursRestants
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $expiration = $this->adherent->date_expiration_cotisation;

        return (new MailMessage)
            ->subject('⏰ Votre cotisation ALIDSC expire dans ' . $this->joursRestants . ' jours')
            ->greeting('Bonjour ' . $this->adherent->prenom . ',')
            ->line('Votre cotisation ALIDSC arrive bientôt à échéance.')
            ->line('**Détails :**')
            ->line('• **Date d\'expiration :** ' . $expiration?->translatedFormat('d F Y'))
            ->line('• **Jours restants :** ' . $this->joursRestants)
            ->line('• **Montant de la cotisation :** '
                . number_format($this->adherent->cotisation_montant, 0, ',', ' ') . ' FCFA')
            ->line('Pour continuer à accéder à votre espace adhérent, à vos documents et aux actualités internes, merci de renouveler votre cotisation.')
            ->line('Un responsable ALIDSC vous contactera prochainement pour convenir du paiement (Mobile Money, espèces ou virement).')
            ->action('Contacter l\'ALIDSC', 'https://wa.me/237696506940')
            ->line('Merci pour votre engagement à nos côtés.')
            ->salutation('Cordialement, l\'équipe ALIDSC');
    }
}