<?php

namespace App\Notifications\Backend;

use App\Classes\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordEmailSend extends Notification
{
    use Queueable;

    public $email;

    public $token;

    public $role_type;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($email,$token,$role_type)
    {
        $this->email = $email;
        $this->token = $token;
        $this->role_type = $role_type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        //dd($this->token);
        return (new MailMessage)
            ->subject(Email::makeSubject('Reset Password Link'))
            ->line('You are receiving this email because Joeyco On-Boarding Admin has created your account for using Joeyco On-Boarding, kindly reset your password and login to your account.')
            ->action('Reset Password', route('password.reset', [$this->email,$this->token,$this->role_type]))
            ->line('If you did not request a password reset, no further action is required.');
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
        ];
    }
}
