<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NouveauMessageContact extends Notification
{
    use Queueable;

    public function __construct(public Message $message) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nouveau message : ' . $this->message->sujet)
            ->greeting('Bonjour l\'equipe ALIDSC,')
            ->line('Vous avez recu un nouveau message via le formulaire de contact.')
            ->line('**Nom :** ' . $this->message->nom)
            ->line('**Email :** ' . $this->message->email)
            ->line('**Telephone :** ' . ($this->message->telephone ?? 'Non renseigne'))
            ->line('**Sujet :** ' . $this->message->sujet)
            ->line('**Message :**')
            ->line($this->message->contenu)
            ->action('Voir dans l\'admin', url('/admin/messages/' . $this->message->id))
            ->line('Repondez rapidement a ' . $this->message->email)
            ->salutation('Cordialement, l\'equipe ALIDSC');
    }
}