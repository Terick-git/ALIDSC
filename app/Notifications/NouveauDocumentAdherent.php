<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NouveauDocumentAdherent extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Document $document) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $categorieLabel = $this->document->categorie_label;

        return (new MailMessage)
            ->subject('📄 Nouveau document disponible — ' . $this->document->titre)
            ->greeting('Bonjour ' . $notifiable->prenom . ',')
            ->line('Un nouveau document vient d\'être ajouté à votre espace adhérent.')
            ->line('**Récapitulatif :**')
            ->line('• **Titre :** ' . $this->document->titre)
            ->line('• **Catégorie :** ' . $categorieLabel)
            ->when($this->document->description, function ($mail) {
                return $mail->line('• **Description :** ' . $this->document->description);
            })
            ->action('Accéder à mon espace', route('adherent.documents'))
            ->line('Ce document est réservé aux adhérents à jour de leur cotisation.')
            ->salutation('Cordialement, l\'équipe ALIDSC');
    }
}