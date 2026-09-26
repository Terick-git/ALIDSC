<?php

namespace App\Notifications;

use App\Models\Adherent;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NouvelleDemandeAdhesion extends Notification
{
    use Queueable;

    public function __construct(public Adherent $adherent) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nouvelle demande d\'adhésion : ' . $this->adherent->nom_complet)
            ->greeting('Bonjour l\'équipe ALIDSC,')
            ->line('Une nouvelle demande d\'adhésion vient d\'être déposée.')
            ->line('**Nom complet :** ' . $this->adherent->nom_complet)
            ->line('**Catégorie souhaitée :** ' . $this->adherent->categorie_label)
            ->line('**Email :** ' . $this->adherent->email)
            ->line('**Téléphone :** ' . $this->adherent->telephone)
            ->line('**Établissement :** ' . $this->adherent->etablissement)
            ->line('**Ville :** ' . ($this->adherent->ville ?? 'Non renseignée'))
            ->action('Voir la demande', url('/admin/adherents/' . $this->adherent->id))
            ->line('Merci de traiter cette demande dans les plus brefs délais.')
            ->salutation('Cordialement, l\'équipe ALIDSC');
    }
}

