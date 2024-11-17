<?php

namespace App\Http\Controllers\Api;

use App\Data\ApplicationData;
use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Notifications\TelegramNotificationNewApplication;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;
use NotificationChannels\Telegram\TelegramChannel;


class ApplicationController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validatedData = ApplicationData::validate($request->all());
            Application::create($validatedData);

            $chatIds = [
                992083441,
                6251517297,
            ];
            // Отправляем уведомление каждому идентификатору чата
            foreach ($chatIds as $chatId) {
                Notification::route('telegram', $chatId)->notify(new TelegramNotificationNewApplication($validatedData));
            }

            return response()->json(['status' => 'success'], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->validator->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Произошла ошибка на сервере. Пожалуйста, попробуйте позже.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
