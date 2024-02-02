<?php

namespace App\Notifications\Backend;

use App\Classes\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NotTrainedNotification extends Notification
{
    use Queueable;


    /**
     * Create a new notification instance.
     * @param string $status
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable) : array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable) : MailMessage
    {
        //$email = base64_encode($notifiable->email);
        $mailMessage = (new MailMessage)->greeting($notifiable->display_name);

        $mailMessage = (new MailMessage)->greeting('Hello, ' . $notifiable->display_name);
        $mailMessage = $mailMessage->subject(Email::makeSubject('Training Pending'));
        $mailMessage = $mailMessage->line('You do not attend training,kindly training attend as soon as possible ');
        $mailMessage = $mailMessage->line('Thank you for using our application!');
        return $mailMessage;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable) : array
    {
        return [
            //
        ];
    }
}
