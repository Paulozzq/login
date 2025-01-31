<?php

namespace App\Arch\Domain\UseCase\Auth;

use App\Arch\Domain\Response\BaseResponse;
use App\Arch\Domain\UseCase\BaseCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class RefreshTokenUseCase extends BaseCase
{
    public function apply(): BaseCase
    {
        $refreshToken = $this->getAttributes()['refresh_token'];

        try {
            // Verificar el refresh token
            $user = JWTAuth::setToken($refreshToken)->authenticate();

            // Generar un nuevo access token
            $newAccessToken = JWTAuth::fromUser($user);

            $this->setResponse('Token refreshed successfully', [
                'access_token' => $newAccessToken,
                'token_type' => 'bearer',
                'expires_in' => JWTAuth::factory()->getTTL() * 60,
            ]);
        } catch (\Exception $e) {
            $this->setResponse('Invalid refresh token', []);
        }

        return $this;
    }
}