<?php

namespace App\Notifications;

use App\Models\Adherent;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NouveauMotDePasseAdherent extends Notification
{
    use Queueable;

    public function __construct(public Adherent $adherent, public string $motDePasse) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🔑 Nouveau mot de passe — Espace adhérent ALIDSC')
            ->greeting('Bonjour ' . $this->adherent->prenom . ',')
            ->line('Vous avez demandé la réinitialisation de votre mot de passe.')
            ->line('**Votre nouveau mot de passe temporaire :**')
            ->line('```' . $this->motDePasse . '```')
            ->line('Connectez-vous avec ce mot de passe puis **changez-le immédiatement** dans votre profil.')
            ->action('Accéder à mon espace', url('/adherent/login'))
            ->line('Si vous n\'êtes pas à l\'origine de cette demande, ignorez cet email.')
            ->salutation('Cordialement, l\'équipe ALIDSC');
    }
}