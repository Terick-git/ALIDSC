<?php

namespace App\Notifications;

use App\Models\Adherent;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdhesionActivee extends Notification
{
    use Queueable;

    public function __construct(
        public Adherent $adherent,
        public string $motDePasse
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🎉 Votre adhésion ALIDSC est active !')
            ->greeting('Bienvenue ' . $this->adherent->prenom . ' !')
            ->line('Votre adhésion à l\'ALIDSC est maintenant **officiellement active**.')
            ->line('**Récapitulatif de votre adhésion :**')
            ->line('• **Nom complet :** ' . $this->adherent->nom_complet)
            ->line('• **Catégorie :** ' . $this->adherent->categorie_label)
            ->line('• **Montant de la cotisation :** ' . number_format($this->adherent->cotisation_montant, 0, ',', ' ') . ' FCFA')
            ->line('• **Date d\'activation :** ' . now()->translatedFormat('d F Y'))
            ->line('**Vos identifiants de connexion :**')
            ->line('Email : ' . $this->adherent->email)
            ->line('Mot de passe temporaire : **' . $this->motDePasse . '**')
            ->line('⚠️ **À votre première connexion, vous serez automatiquement invité à changer ce mot de passe.**')
            ->action('Accéder à mon espace', url('/adherent/login'))
            ->line('Merci encore pour votre engagement à nos côtés. Ensemble, nous construisons l\'avenir de la jeunesse camerounaise.')
            ->salutation('Cordialement, l\'équipe ALIDSC');
    }
}