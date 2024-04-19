<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\RegistrationRequest;
class AuthController extends BaseController
{
    public function registration(RegistrationRequest $request, AuthService $authService): JsonResponse
    {
        $result = $authService->registration($request->all());
        return $this->sendResponse($result);
    }

    public function login(LoginRequest $request, AuthService $authService): JsonResponse
    {
        $result = $authService->login($request->all());
        return $this->sendResponse($result);
    }

    public function me()
    {
        return $this->sendResponse(
            [
                'user' => auth()->user()
            ]
        );
    }

    public function logout(): JsonResponse
    {
        return $this->sendResponse(
            [
                'success' => true,
            ]
        );
    }
}
