<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="User Login",
     *     tags={"Auth"},
     *     operationId="loginUser",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Login successful"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="user", type="object"),
     *                 @OA\Property(property="token", type="string", example="your-access-token-here")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The email field is required."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login($request->only('email', 'password'));

        return response()->json([
            'message' => 'Login successful',
            'data' => [
                'user' => $result['user'],
                'token' => $result['token'],
            ],
        ]);
    }


    public function logout(Request $request): JsonResponse
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Logout successful',
        ]);
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $result = $this->authService->sendForgotPasswordLink($request->email);

        if ($result['success']) {
            return response()->json([
                'message' => $result['message']
            ], 200);
        }

        return response()->json([
            'message' => $result['message']
        ], 422);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $result = $this->authService->resetPassword($request->only(
            'token', 'email', 'password', 'password_confirmation'
        ));

        if ($result['success']) {
            return response()->json([
                'message' => $result['message']
            ], 200);
        }

        return response()->json([
            'message' => $result['message']
        ], 422);
    }

}
