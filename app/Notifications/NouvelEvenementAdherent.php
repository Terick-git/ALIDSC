<?php

namespace App\Notifications;

use App\Models\Evenement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NouvelEvenementAdherent extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Evenement $evenement) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('📅 Nouvel événement ALIDSC — ' . $this->evenement->titre)
            ->greeting('Bonjour ' . $notifiable->prenom . ',')
            ->line('Un nouvel événement ALIDSC vient d\'être annoncé.')
            ->line('**Récapitulatif :**')
            ->line('• **Titre :** ' . $this->evenement->titre)
            ->line('• **Date :** ' . $this->evenement->date_debut->translatedFormat('d F Y à H\hi'))
            ->line('• **Lieu :** ' . $this->evenement->lieu
                . ($this->evenement->ville ? ' — ' . $this->evenement->ville : ''))
            ->line($this->evenement->extrait);

        if ($this->evenement->lien_inscription) {
            $mail->action('S\'inscrire', $this->evenement->lien_inscription);
        } else {
            $mail->action('Voir les détails', route('evenements.show', $this->evenement->slug));
        }

        return $mail
            ->line('Nous espérons vous y voir nombreux.')
            ->salutation('Cordialement, l\'équipe ALIDSC');
    }
}