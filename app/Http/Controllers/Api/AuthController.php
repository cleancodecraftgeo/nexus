<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
     /**
     * Yeni istifadəçi qeydiyyatı
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'=>['required','string','max:255'],
            'email'=>['required','email','max:255','unique:users,email'],
            'password'=>['required','string','min:8','confirmed'],
        ]);

        $user = User::create([
            'name'=>$validated['name'],
            'email'=>$validated['email'],
            'password'=>$validated['password'],
            'role'=>UserRole::Customer,
        ]);

        Auth::login($user);

        return response()->json([
            'message'=>'Register Successfully',
            'user'=>$user,
        ],201);
    }

    /**
     * İstifadəçi girişi
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'=>['required','email'],
            'password'=>['required','string']
        ]);

        if(! Auth::attempt($credentials))
            {
                return response()->json([
                    'message'=>'Invalid credentials.'
                ],422);
            }

        $request->session()->regenerate();

        return response()->json([
            "message"=>'login successfully',
            "user"=>$request->user()
        ]);
    }

    /**
     * current user info
     */
    public function user(Request $request)
    {
        return response()->json([
            'user'=>$request->user(),
        ]);
    }

     /**
     * logout
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            "message" =>"logout successfully"
        ]);
    }
}
