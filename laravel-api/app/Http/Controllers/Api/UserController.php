<?php

namespace App\Http\Controllers\Api;

use App\Enumerations\Models\UserModelEnum;
use App\Enumerations\Models\UserProfileModelEnum;
use App\Http\Controllers\Controller;
use App\Mail\EmailVerification;
use App\Models\EmailVerificationCode;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function search(Request $request) {
        $user = Auth::user();
        $query = $request->get('searchQuery');

        if (empty($query)) {
            return response()->json([]);
        }

        // search all the email that starts with the query
        $matchUsers = User::with('profile')
                        ->startsWith(UserModelEnum::getEmail(), $query)
                        ->where(UserModelEnum::getId(), '!=', $user->getId())
                        ->get();

        // for the username
        /*if (count($matchUsers) === 0) {
            $matchUsers = User::contains(UserModelEnum::getUsername(), $query)->get();
        }*/

        return response()->json($matchUsers);
    }
}
