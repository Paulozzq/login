<?php

namespace App\Http\Controllers;

use App\Arch\Domain\UseCase\Auth\LoginUseCase;
use App\Arch\Domain\UseCase\Auth\RefreshTokenUseCase;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request, LoginUseCase $loginUseCase)
    {
        $loginUseCase->setRequest($request)->apply();
        $response = $loginUseCase->getResponse();

        return response()->json([
            'message' => $response->getMessage(),
            'data' => $response->getData(),
        ], $response->getMessage() === 'Unauthorized' ? 401 : 200);
    }

    public function refresh(Request $request, RefreshTokenUseCase $refreshTokenUseCase)
    {
        $refreshTokenUseCase->setRequest($request)->apply();
        $response = $refreshTokenUseCase->getResponse();

        return response()->json([
            'message' => $response->getMessage(),
            'data' => $response->getData(),
        ], $response->getMessage() === 'Invalid refresh token' ? 401 : 200);
    }
}