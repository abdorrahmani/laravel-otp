<?php

namespace App\Http\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class SmsService
{
    /**
     * In this case we use sms.ir panel service
     * @throws ConnectionException
     */
    public function sendVerificationCode(string $tel, int $code): array
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'text/plain',
            'x-api-key' => config('services.SMSir.token'),
        ])->post('https://api.sms.ir/v1/send/verify', [
            'Mobile' => $tel,
            'TemplateId' => 'YOUR_TEMPLATE_ID',
            'Parameters' => [
                [
                    'name' => 'code',
                    'value' => $code,
                ],
            ],
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return [
            'message' => 'خطا در ارسال پیامک',
            'error' => $response->json(),
        ];
    }
}
