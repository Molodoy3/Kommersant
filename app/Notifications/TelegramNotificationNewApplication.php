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
    public function toTelegram(object $notifiable): TelegramFile
    {
        $filePath = Storage::disk('public')->path('properties/1/image.jpeg');
        //dd($filePath);
        //$file = new UploadedFile($filePath, 'image.jpeg', null, null, true);
        //dd($file);
        return TelegramFile::create()
            ->content("Вацочек лошочек😁😊😜")
            ->file($filePath, 'jpeg');
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
