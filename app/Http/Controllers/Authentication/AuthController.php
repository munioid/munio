<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\LoginRequest;
use App\Services\Auth\AuthService;
use Throwable;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService,
    ) {}

    public function login(LoginRequest $request)
    {
        try {
            $data = $request->validated();

            $result = $this->authService->login($data);

            return response()->json([
                'token' => $result['token'],
                'token_type' => $result['token_type']
            ], 200);
        } catch (Throwable $th) {
            return $this->respondWithError($th->getMessage(), $th->getCode());
        }
    }
}
