<?php

namespace App\Notifications;

use App\Models\Adherent;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DemandeRefusee extends Notification
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
            ->subject('Votre demande d\'adhésion — ALIDSC')
            ->greeting('Bonjour ' . $this->adherent->prenom . ',')
            ->line('Nous vous remercions pour l\'intérêt que vous portez à l\'ALIDSC.')
            ->line('Après étude de votre dossier, nous ne sommes malheureusement pas en mesure de donner une suite favorable à votre demande pour le motif suivant :')
            ->line('**Motif :** ' . ($this->adherent->motif_refus ?? 'Non précisé'))
            ->line('Si vous pensez qu\'il s\'agit d\'une erreur ou si vous souhaitez apporter des éléments complémentaires, n\'hésitez pas à nous contacter directement.')
            ->action('Nous contacter', 'https://wa.me/237696506940')
            ->salutation('Cordialement, l\'équipe ALIDSC');
    }
}
