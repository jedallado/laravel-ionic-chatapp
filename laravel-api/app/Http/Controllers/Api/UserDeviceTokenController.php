<?php

namespace App\Http\Controllers\Api;

use App\Enumerations\Models\UserDeviceTokenEnum;
use App\Http\Controllers\Controller;
use App\Models\UserDeviceToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDeviceTokenController extends Controller
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
        $data = $request->only(UserDeviceTokenEnum::fillable());

        $userId = Auth::id();
        $token = $data['token'] ?? '';

        $tokenExist = UserDeviceToken::ofUserId($userId)
                        ->where($token, )->count() > 0;

        if (!$tokenExist) {
            $userDeviceToken = UserDeviceToken::create($data);

            return response()->json($userDeviceToken, 201);
        }

        return response()->json(null, 204);
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
}
