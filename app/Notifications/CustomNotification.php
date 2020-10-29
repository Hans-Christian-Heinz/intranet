<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomNotification extends Notification
{
    use Queueable;

    /**
     * @var string
     */
    private $absender;
    /**
     * @var string
     */
    private $betreff;
    /**
     * @var string
     */
    private $inhalt;

    /**
     * CustomNotification constructor.
     *
     * @param string $absender
     * @param string $betreff
     * @param string $inhalt
     */
    public function __construct(string $absender, string $betreff, string $inhalt)
    {
        $this->absender = $absender;
        $this->betreff = $betreff;
        $this->inhalt = $inhalt;
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
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'absender' => $this->absender,
            'betreff' => $this->betreff,
            'inhalt' => $this->inhalt,
        ];
    }
}
