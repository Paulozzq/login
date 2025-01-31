<?php

namespace App\Arch\Infrastructure\Auth;

use App\Arch\Domain\UseCase\BaseIterator;
use App\Arch\Infrastructure\BaseService;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class AuthService extends BaseService
{
    public function execute()
    {
        $credentials = $this->iterator->transform();

        if (!$token = JWTAuth::attempt($credentials)) {
            $this->iterator->feedback(['error' => 'Unauthorized']);
            return;
        }

        $user = Auth::user();

        $this->iterator->feedback([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->getOnlyRoleNames(),
            ],
        ]);
    }
}