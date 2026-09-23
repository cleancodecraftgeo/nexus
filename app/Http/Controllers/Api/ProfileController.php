<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function update(UpdateProfileRequest $request): JsonResponse
    {
            $user = $request->user();

            $user->update(
                $request->validated()
            );

            return response()->json([
                "message"=>'Profile updated successfully. ',
                'user'=>$user->fresh(),
            ]);
    }
}
