<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserFollowNotification extends Notification
{
    use Queueable;
    public $user;
    public $text;
    public $link;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->text = "کاربر {$this->user->name} شما را دنبال می کند";
        $this->link = "/@{$this->user->username}";
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return !! $notifiable->email_on_follow ?  ['mail', 'database'] : ['database'] ;
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
        ->subject('شما دنبال کننده جدید دارید! ')
        ->line($this->text)
        ->action('پست های کاربر ', $this->link);
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
            'text' => $this->text,
            'link' => $this->link
        ];
    }
}
