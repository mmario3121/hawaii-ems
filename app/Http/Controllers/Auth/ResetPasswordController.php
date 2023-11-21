<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\ForgetPasswordRequest;
use App\Mail\ForgetPasswordMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ResetPasswordController extends Controller
{
    public function forgetPassword(ForgetPasswordRequest $request)
    {
        $user = User::query()
            ->where('email', '=', $request->email)
            ->first();

        if (!$user) {
            return new JsonResponse([
                'message' => 'Пользотваель не найдено!'
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $token = Str::random(64);

            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => now()
            ]);

            Mail::to($user->email)->send(new ForgetPasswordMail($user->email, $token));

            return new JsonResponse([
                'message' => 'Ссылка для сброса пароля успешно отправлено!'
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function resetPassword(Request $request)
    {
        $reset_password = PasswordResets::where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if ($reset_password) {
            $user = User::where('email', $request->email)->first();

            if ($user) {
                $request->validate([
                    'password' => 'required|string|confirmed|min:6',
                ], [
                    'password.confirmed' => 'Пароли не совпадают',
                    'password.required' => 'Введите пароль',
                ]);

                $user->password = Hash::make($request->password);

                if ($user->update()) {
                    PasswordResets::where('email', $request->email)->delete();

                    return response()->json(['message' => 'Пароль успешно восстановлен'], 200);
                }
                return response()->json(['message' => 'Ошибка при восстановлении'], 500);
            }
            return response()->json(['message' => 'Пользователь с логином ' . $request->email . ' не зарегистрирован'], 403);
        }

        return response()->json(['message' => 'Данные для восстановления пароля не действительный'], 500);
    }

}
