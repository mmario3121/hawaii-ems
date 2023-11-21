<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::query()->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'phone' => $request->phone,
        ])->assignRole('user');

        $tokenResult = $user->createToken('apiMentorMeToken');
        $token = $tokenResult->plainTextToken;

        $personalAccessToken = PersonalAccessToken::findToken($token);
        $expiration = now()->addDays(7);
        $user->tokens()->where('id', $personalAccessToken->id)->update([
            'expires_at' => $expiration,
        ]);

        if ($request->remember == 1) {
            $rememberToken = Str::random(60);
            $user->forceFill([
                'remember_token' => hash('sha256', $rememberToken),
            ])->save();
        }

        return new JsonResponse([
            'token' => $token,
            'user' => new UserResource($user)
        ], Response::HTTP_OK);
    }
}
