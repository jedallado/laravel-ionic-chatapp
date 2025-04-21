<?php

namespace App\Http\Controllers\Api;

use App\Enumerations\Models\UserModelEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request) {
        $password = $request->get(UserModelEnum::getPassword());
        $deviceName = $request->get('device_name');
        $user = User::where(UserModelEnum::getEmail(), $request->get('email'))->first();

        if (!$user || !$user->isPasswordValid($password)) {
            return response()->json(['message' => 'Invalid username or password.'], 422);
        }

        if (!$user->isVerified()) {
            return response()->json(['verified' => false], 500);
        }

        $token = $user->createToken($deviceName);

        return response()->json(['token' => $token->plainTextToken]);
    }

    public function logout() {
        $user = Auth::user();

        $user->currentAccessToken()->delete();
        return response()->json(null, 204);
    }
}
