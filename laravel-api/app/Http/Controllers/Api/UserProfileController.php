<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    public function getLoggedUserProfile() {
        $profile = Auth::user()->profile;

        return response()->json($profile);
    }
}
