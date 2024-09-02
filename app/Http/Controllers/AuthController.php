<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use App\Http\ExceptionHandle\ExceptionHandle;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ExceptionHandle;
    public function login(Request $request)
    {
        try {
            $inputs = $request->all();

            $email = $inputs['payload']['email'];

            $user = User::where('email', $email)->first();

            if (empty($user)) {
                $user = User::create([
                    "name" => $inputs['payload']['name'],
                    "email" => $inputs['payload']['email'],
                    "password" => Hash::make("12345678"),
                ]);
            }

            $tokens = $this->generateTokens($email);
            return json_encode($tokens);

        }
        catch (\Throwable $th) {
            return $this->handle($th);
        }
    }

    public function generateTokens($email)
    {
        if (Auth::attempt(['email' => $email, 'password' => "12345678"])){

            $accessTokenObj = auth()->user()->createToken('_access_token', [], (new DateTime)->setTime(config('sanctum.access_token_expiry'), 0));
            $refreshTokenObj = auth()->user()->createToken('_refresh_token', [], (new DateTime)->setTime(config('sanctum.refresh_token_expiry'), 0));

            $accessToken = hash('sha256', explode("|", $accessTokenObj->plainTextToken)[1]);
            $refreshToken = hash('sha256', explode("|", $refreshTokenObj->plainTextToken)[1]);

            return [
                '_access_token' => [
                    'token' => $accessToken,
                    // 'expires_at' => $accessTokenObj->plainTextToken,
                ],
                '_refresh_token' => [
                    'token' => $refreshToken,
                    // 'expires_at' => $refreshTokenObj->plainTextToken,
                ],
            ];
        } else {
            throw new \Exception("User not found", 1);
        }
    }
}
