<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
                return response()->json([
                'message' => 'success'
                 ], 200);
        } else {
                return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        // $Withdrawn = User::withTrashed()->where('email', '=', 'email')->get();
        // if($Withdrawn){
        //     return response()->json([
        //     'message' => 'Withdrawal'
        //     ], 401);
        // }
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'message' => 'Logged out'
        ], 200);
    }
}
