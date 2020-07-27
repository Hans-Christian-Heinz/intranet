<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentChangedNotification extends Notification
{
    use Queueable;

    /**
     * @var string
     */
    private $changedBy;
    /**
     * @var string
     */
    private $documentType;

    /**
     * DocumentChangedNotification constructor.
     * @param string $changedBy
     * @param string $documentType
     */
    public function __construct(string $changedBy, string $documentType)
    {
        $this->changedBy = $changedBy;
        $this->documentType = $documentType;
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
            'changedBy' => $this->changedBy,
            'documentType' => $this->documentType,
        ];
    }
}
