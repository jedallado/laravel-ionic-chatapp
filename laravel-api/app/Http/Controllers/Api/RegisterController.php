<?php

namespace App\Http\Controllers\Api;

use App\Enumerations\Models\UserModelEnum;
use App\Enumerations\Models\UserProfileModelEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterPostRequest;
use App\Mail\EmailVerification;
use App\Models\EmailVerificationCode;
use App\Models\User;
use App\Models\UserProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function register(RegisterPostRequest $request) {
        $code = Str::random(6);

        $userData = $request->only(UserModelEnum::fillable());
        $user = User::create($userData);

        try {
            $profile = new UserProfile($request->only(UserProfileModelEnum::fillable()));
            $verificationCode = new EmailVerificationCode(
                ['verification_code' => $code]
            );

            $user->profile()->save($profile);
            $user->verificationCode()->save($verificationCode);

            Mail::to($user->getEmail())->send(new EmailVerification($code));

            return response()->json($user, 201);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            // If there was a problem saving the profile, delete the previously created user
            $user->delete();

            return response()->json(['message' => 'An internal error occurred. Please try again.'], 500);
        }
    }

    public function verify(Request $request) {
        $email = $request->get('email');
        $verificationCode = $request->get('verificationCode');

        $user = User::where(UserModelEnum::getEmail(), $email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 422);
        }

        $code = EmailVerificationCode::where('verification_code', $verificationCode)
                    ->where('user_id', $user->getId())
                    ->first();

        if (!$code) {
            return response()->json(['message' => 'Invalid verification code.'], 422);
        }

        /*$user = $code->user;
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }*/

        $user->{UserModelEnum::getEmailVerifiedAt()} = Carbon::now();
        $user->save();

        return response()->json('success');
    }

    // TODO: For later after beta test
    public function resendVerification() {}
}
