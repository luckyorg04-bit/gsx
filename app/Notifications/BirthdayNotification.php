<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BirthdayNotification extends Notification
{
    use Queueable;

    public $message;
    public $user_id;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message,$user_id)
    {
        //
        $this->message = $message;
        $this->user_id = $user_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('GSX birthday notification')
            ->greeting('Hello')
            ->line((string) $this->message)
            ->action('Open GSX', url('/'))
            ->salutation('GSX');
    }
    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
            'type'  =>  'birthday',
            'id'    =>  $this->user_id,
            'data'  =>  $this->message,
        ];
    }
}