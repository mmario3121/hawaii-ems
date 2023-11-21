<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::query()->where('email', '=', $request->email)->first();

        if (!$user) {
            return new JsonResponse([
                'message' => trans('messages.user_not_found')
            ], Response::HTTP_BAD_REQUEST);
        }

        if (!Hash::check($request->password, $user->password)) {
            return new JsonResponse([
                'message' => trans('messages.email_password_error')
            ], Response::HTTP_BAD_REQUEST);
        }

        if($user->tokens()->where('tokenable_id', $user->id)->exists()) {
            $user->tokens()->delete();
        }

        $tokenResult = $user->createToken('apiMentorMeToken');
        $token = $tokenResult->plainTextToken;

        $personalAccessToken = PersonalAccessToken::findToken($token);
        $expiration = now()->addDays(7);
        $user->tokens()->where('id', $personalAccessToken->id)->update([
            'expires_at' => $expiration,
        ]);

        return new JsonResponse([
            'token' => $token,
            'user' => new UserResource($user)
        ], Response::HTTP_OK);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return new JsonResponse([
            'message' => 'Успешно вышел из системы'
        ], Response::HTTP_OK);
    }
}
