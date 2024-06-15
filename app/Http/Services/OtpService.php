<?php

namespace App\Http\Services;


use App\Models\OtpGeneration;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Random\RandomException;

class OtpService
{
    /**
     * @throws RandomException
     */
    public function generateOtp(int $userId): OtpGeneration
    {
        return OtpGeneration::create([
            'user_id' => $userId,
            'code' => random_int(100000, 999999),
            'token' => Str::uuid(),
        ]);
    }

    public function getUserByTel(string $tel): User
    {
        return User::firstOrCreate(['tel' => $tel]);
    }

    public function verifyOtp(int $code, string $token): array
    {
        $otp = OtpGeneration::where('code', $code)
            ->where('token', $token)
            ->first();

        if (! $otp) {
            return ['success' => false, 'message' => 'کد یا توکن صحیح نیست'];
        }

        if (Carbon::now()->greaterThan(Carbon::parse($otp->expires_at))) {
            return ['success' => false, 'message' => 'کد منقضی شده است'];
        }

        return ['success' => true, 'user' => $otp->user];
    }
}
