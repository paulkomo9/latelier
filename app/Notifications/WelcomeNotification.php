<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                ->subject('Welcome to L’Atelier Aqua Fitness 🌊')
                ->greeting('Welcome aboard, ' . $notifiable->firstname . '!')
                ->line('We’re thrilled to have you as part of our ladies-only fitness community.')
                ->line('L’Atelier Aqua Fitness is more than just a place to work out — it’s a space for self-care, strength, and supportive sisterhood.')
                ->line('Whether you’re diving into your first aqua session or returning to flow with us, we’re here to support your wellness journey every step of the way.')
                ->action('Explore Your Dashboard', url('/dashboard'))
                ->line('💧 Let’s make waves together!')
                ->line('Need help getting started? [Click here for your welcome guide](https://latelieraquafitness.fit/welcome-guide)')
                ->salutation('With strength and style,  
        The L’Atelier Aqua Fitness Team');

    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
