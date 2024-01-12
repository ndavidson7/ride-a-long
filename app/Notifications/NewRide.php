<?php

namespace App\Notifications;

use App\Models\Ride;
use Illuminate\Notifications\Messages\MailMessage;

class NewRide extends BaseNotification
{
    /**
     * Create a new notification instance.
     */
    public function __construct(Ride $ride)
    {
        $this->url = route('rides.show', $ride->id);
        $this->message = "A new ride matching your alert has been posted!";
    }

    // /**
    //  * Get the notification's delivery channels.
    //  *
    //  * @return array<int, string>
    //  */
    // public function via(object $notifiable): array
    // {
    //     return ['mail'];
    // }

    // /**
    //  * Get the mail representation of the notification.
    //  */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //         ->line('The introduction to the notification.')
    //         ->action('Notification Action', url('/'))
    //         ->line('Thank you for using our application!');
    // }
}
