<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Validator;
use App\Models\User;

class AuthController extends Controller
{
    //
    function register(Request $request)
    {
        // Validate the incoming request data.
        $request->validate([
            'name'             => 'required|string|min:5',
            'email'            => 'required|string|unique:users',
            'password'         => 'required|string|min:5',
            'password_confirmation' => 'required|string|same:password', // Ensure confirm_password matches password
        ]);

        // Create a new User instance and set its properties.
        $user = new User();
        $user->name  = $request->name;
        $user->email = $request->email;
        // Hash the password before storing it.
        $user->password = bcrypt($request->password);

        if ($user->save()) {
            // Create a personal access token for the user.
            $token = $user->createToken('Personal Access Token');

            return response()->json([
                'type'  => 'success',
                'token' => $token->plainTextToken,
                'user'  => $user
            ]);
        }

        return response()->json([
            'type' => 'error',
            'msg'  => 'Something went wrong, please check.',
        ]);
    }

    function login(Request $request){
        $request->validate([
            'email'=>'required|string',
            'password'=>'required|string'
        ]);   
        if(!Auth::attempt($request->all())){
            return response()->json(['message'=>'Unauthorized'],400);
        }
     
        $user = User::where(['email'=>$request->email])->first();
        return response()->json(['type'=>'success', 'user'=>$user, 'token'=>$user->createToken('auth-token')->plainTextToken]);
    }
}
