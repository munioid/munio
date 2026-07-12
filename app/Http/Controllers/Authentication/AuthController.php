<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\LoginRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;
use Throwable;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService,
    ) {}

    /**
     * Login Function
     */
    public function login(LoginRequest $request)
    {
        try {
            $data = $request->validated();

            $result = $this->authService->login($data);

            return response()->json([
                'success' => true,
                'token' => $result['token'],
                'token_type' => $result['token_type']
            ], 200);
        } catch (Throwable $th) {
            $httpCode = $th->getCode() != 0 ? $th->getCode() : 500;
            return $this->respondWithError($th->getMessage(), $httpCode);
        }
    }

    /**
     * Logout Function
     */
    public function logout(Request $request)
    {
        $user = $request->user();

        $user->tokens->each(function ($token) {
            $token->revoke();
        });

        return $this->respondSuccess('Logout berhasil.');
    }
}
