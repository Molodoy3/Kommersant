<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use NotificationChannels\Telegram\TelegramFile;
use NotificationChannels\Telegram\TelegramMessage;

class TelegramNotificationNewApplication extends Notification
{
    use Queueable;
    protected $applicationData;
    /**
     * Create a new notification instance.
     */
    public function __construct($applicationData)
    {
        $this->applicationData = $applicationData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['telegram'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toTelegram(object $notifiable): TelegramMessage
    {
        $filePath = Storage::disk('public')->path('properties/1/image.jpeg');
        //dd($this->applicationData);
        $message = TelegramMessage::create()
            ->line("Пришла новая заявка!")
            ->line("");

        if ($this->applicationData->service) {
            $message->line("Услуга: " . $this->applicationData->service);
        }

        $message->line("Имя: " . $this->applicationData->name)
            ->line("Телефон: " . $this->applicationData->telephone)
            ->line("Сообщение: " . $this->applicationData->comment);

        if ($this->applicationData->user_price) {
            $message->line("Пользовательская цена для торга: " . $this->applicationData->user_price);
        }

// Возвращаем сообщение
        return $message;
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
