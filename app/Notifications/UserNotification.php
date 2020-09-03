<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;


class UserNotification extends Notification
{
    use Queueable;

    public $data;
    public $type;
    public $userRole;
    // public $userID;

    public function __construct($data, $type, $userRole)
    {
        $this->data = $data;
        $this->type = $type;
        $this->userRole = $userRole;
        // $this->userID = $userID;
    }

    public function via($notifiable)
    {
        // return ['database', 'broadcast'];
        return ['database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'data' => $this->data,
            'type' => $this->type,
            'userRole' => $this->userRole
            // ,'userID' => $this->userID
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'data' => $this->data,
            'type' => $this->type,
            'userRole' => $this->userRole
            // ,'userID' => $this->userID
        ]);
    }
}
