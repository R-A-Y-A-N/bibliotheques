<?php

namespace App\Notifications;
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class OverdueNotification extends Notification
{
    use Queueable;

    protected $loan;

    public function __construct($loan)
    {
        $this->loan = $loan;
    }

    // 🔥 ON UTILISE LA BASE DE DONNÉES
    public function via($notifiable)
    {
        return ['database'];
    }

    // 📩 Données stockées en base
    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Vous avez un emprunt en retard !',
            'loan_id' => $this->loan->id,
            'date_retour' => $this->loan->date_retour,
        ];
    }
}
