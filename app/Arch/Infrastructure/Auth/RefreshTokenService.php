<?php

namespace App\Arch\Infrastructure\Auth;

use App\Arch\Domain\UseCase\BaseIterator;
use App\Arch\Infrastructure\BaseService;
use Tymon\JWTAuth\Facades\JWTAuth;

class RefreshTokenService extends BaseService
{
    public function execute()
    {
        try {
            $newToken = JWTAuth::refresh(JWTAuth::getToken());

            $this->iterator->feedback([
                'access_token' => $newToken,
                'token_type' => 'bearer',
                'expires_in' => JWTAuth::factory()->getTTL() * 60,
            ]);
        } catch (\Exception $e) {
            $this->iterator->feedback(['error' => 'Token could not be refreshed']);
        }
    }
}