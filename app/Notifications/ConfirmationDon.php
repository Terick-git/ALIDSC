<?php

namespace App\Notifications;

use App\Models\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class ConfirmationDon extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Donation $donation) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        // Lien signé valable 30 jours (le reçu contient des données personnelles)
        $lienRecu = URL::temporarySignedRoute(
            'don.recu',
            now()->addDays(30),
            ['reference' => $this->donation->reference]
        );

        return (new MailMessage)
            ->subject('Merci pour votre don — ALIDSC')
            ->greeting('Merci ' . $this->donation->nom . ' !')
            ->line('Nous avons bien reçu votre don de **' . $this->donation->montant_formate . '**.')
            ->line('**Récapitulatif :**')
            ->line('• **Référence :** ' . $this->donation->reference)
            ->line('• **Montant :** ' . $this->donation->montant_formate)
            ->line('• **Moyen :** ' . $this->donation->moyen_label)
            ->line('• **Date :** ' . $this->donation->paid_at?->translatedFormat('d F Y à H\hi'))
            ->line('Grâce à vous, **' . $this->donation->kit_equivalent . ' ' .
                ($this->donation->kit_equivalent > 1 ? 'enfants seront équipés' : 'enfant sera équipé') .
                '** pour toute une année scolaire.')
            ->action('Télécharger mon reçu', $lienRecu)
            ->line('Chaque don est tracé et fait l\'objet d\'un reçu officiel. Merci de votre engagement à nos côtés.')
            ->salutation('Cordialement, l\'équipe ALIDSC');
    }
}