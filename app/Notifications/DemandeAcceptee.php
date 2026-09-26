<?php

namespace App\Notifications;

use App\Models\Adherent;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DemandeAcceptee extends Notification
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
            ->subject('✅ Votre demande d\'adhésion a été acceptée — ALIDSC')
            ->greeting('Bonjour ' . $this->adherent->prenom . ',')
            ->line('Nous avons le plaisir de vous informer que votre demande d\'adhésion à l\'ALIDSC a été **acceptée**.')
            ->line('**Récapitulatif :**')
            ->line('• **Nom complet :** ' . $this->adherent->nom_complet)
            ->line('• **Catégorie :** ' . $this->adherent->categorie_label)
            ->line('• **Email :** ' . $this->adherent->email)
            ->line('**Prochaine étape : le paiement de votre cotisation.**')
            ->line('Un responsable ALIDSC vous contactera très prochainement au **' . $this->adherent->telephone . '** pour convenir du montant et du mode de paiement (Mobile Money, espèces ou virement).')
            ->action('Nous contacter sur WhatsApp', 'https://wa.me/237696506940')
            ->line('Merci de votre engagement à nos côtés pour l\'éducation au Cameroun.')
            ->salutation('Cordialement, l\'équipe ALIDSC');
    }
}
