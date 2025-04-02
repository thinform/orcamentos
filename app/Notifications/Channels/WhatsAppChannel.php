<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppChannel
{
    protected $apiUrl = 'https://graph.facebook.com/v17.0/';
    protected $phoneNumberId;
    protected $accessToken;

    public function __construct()
    {
        $this->phoneNumberId = config('services.whatsapp.phone_number_id');
        $this->accessToken = config('services.whatsapp.access_token');
    }

    public function send($notifiable, Notification $notification)
    {
        if (!method_exists($notification, 'toWhatsApp')) {
            return;
        }

        $message = $notification->toWhatsApp($notifiable);

        if (empty($message) || empty($notifiable->phone)) {
            return;
        }

        try {
            $response = Http::withToken($this->accessToken)
                ->post($this->apiUrl . $this->phoneNumberId . '/messages', [
                    'messaging_product' => 'whatsapp',
                    'to' => $this->formatPhoneNumber($notifiable->phone),
                    'type' => 'text',
                    'text' => [
                        'body' => $message
                    ]
                ]);

            if (!$response->successful()) {
                Log::error('WhatsApp API Error', [
                    'response' => $response->json(),
                    'phone' => $notifiable->phone
                ]);
            }

            return $response;
        } catch (\Exception $e) {
            Log::error('WhatsApp Notification Error', [
                'message' => $e->getMessage(),
                'phone' => $notifiable->phone
            ]);
        }
    }

    protected function formatPhoneNumber($phone)
    {
        // Remove todos os caracteres não numéricos
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Adiciona o código do país (Brasil - 55) se não estiver presente
        if (strlen($phone) === 11 || strlen($phone) === 10) {
            $phone = '55' . $phone;
        }
        
        return $phone;
    }
} 