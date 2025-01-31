<?php

namespace App\Arch\Domain\UseCase\Auth;

use App\Arch\Domain\Response\BaseResponse;
use App\Arch\Domain\UseCase\BaseCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginUseCase extends BaseCase
{
    public function apply(): BaseCase
    {
        $credentials = $this->getAttributes();

        if (!$token = JWTAuth::attempt($credentials)) {
            $this->setResponse('Unauthorized', []);
            return $this;
        }

        // Generar el refresh token (con un tiempo de expiración más largo)
        $refreshToken = JWTAuth::customClaims(['exp' => now()->addDays(7)->timestamp])->fromUser(auth()->user());

        $this->setResponse('Login successful', [
            'access_token' => $token,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ]);

        return $this;
    }
}