<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class AlertNotification extends Notification
{
    use Queueable;

    protected $template;

    
    /**
     * Create a new notification instance.
     */
    public function __construct(array $template)
    {
        $this->template = $template;
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
                ->greeting('Hello ' . ($notifiable->firstname ?? 'there') . '!')
                ->subject($this->template['subject'])
                ->line($this->template['body'])
                ->line(new HtmlString($this->template['action']))
                ->line(new HtmlString($this->template['module_name']))
                ->line(new HtmlString($this->template['item_affected']))
                ->line(new HtmlString($this->template['action_by']))
                ->line(new HtmlString($this->template['datetime']))
                ->action($this->template['actionText'], $this->template['actionURL'])
                ->line($this->template['thanks'])
                ->salutation(__('Regards') . "  \n" . config('app.name'));
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
