<?php

namespace App\Http\Controllers\Api\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\TelVerificationRequest;
use App\Http\Services\OtpService;
use App\Http\Services\SmsService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Random\RandomException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    protected OtpService $otpService;

    protected SmsService $smsService;

    public function __construct(OtpService $otpService, SmsService $smsService)
    {
        $this->otpService = $otpService;
        $this->smsService = $smsService;
    }

    /**
     * @throws RandomException
     * @throws ConnectionException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $user = $this->otpService->getUserByTel($validatedData['tel']);

        $otp = $this->otpService->generateOtp($user->id);

        $sms = $this->smsService->sendVerificationCode($validatedData['tel'], $otp->code);

        return response()->json([
            'otp_token' => $otp->token,
            'message' => $sms,
        ],Response::HTTP_OK);
    }

    public function verification(TelVerificationRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $verificationResult = $this->otpService->verifyOtp($validated['code'], $validated['token']);

        if (! $verificationResult['success']) {
            return response()->json(['message' => $verificationResult['message']], 400);
        }

        $token = $verificationResult['user']->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'کد صحیح است',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }
}
