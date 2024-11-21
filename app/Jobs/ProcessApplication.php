<?php

namespace App\Jobs;

use App\Models\Application;
use App\Models\Property;
use App\Models\Service;
use App\Notifications\TelegramNotificationNewApplication;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Notification;

class ProcessApplication implements ShouldQueue
{
    use Queueable;
    protected $validatedData;
    /**
     * Create a new job instance.
     */
    public function __construct($validatedData)
    {
        $this->validatedData = $validatedData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $application = Application::create($this->validatedData);

        $service = $application->service_id
            ? Service::find($application->service_id)
            : ($application->property_id ? Property::find($application->property_id) : null);

        if ($service)
            $application->service = $service->title ?: $service->name;


        $chatIds = [
            992083441,
            6251517297, //Вася
            //5293252073, //Джавад
        ];
        // Отправляем уведомление каждому идентификатору чата
        foreach ($chatIds as $chatId) {
            Notification::route('telegram', $chatId)->notify(new TelegramNotificationNewApplication($application));
        }
    }
}
