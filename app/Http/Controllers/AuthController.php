<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Validator;
use App\MOdels\User;

class AuthController extends Controller
{
    //
    function register(Request $request){

        //\Log::info('Request Data:', $request->all()); // Log request data

        $request->validate([
            'name'=>'required|string|min:5',
            'email'=>'required|string|unique:users',
            'password'=>'required|string',
        ]);

        $user = new User($request->all());
        if($user->save()){
            $token = $user->createToken('Personal Access Token');
            return response()->json([
                'type'=> 'success',
                'token'=>$token->plainTextToken
            ]);
        }
        return response()->json([
            'type'=> 'error',
            'msg' =>'Something went wrong please check'
        ]);
    }
    function login(Request $request){
        $request->validate([
            'email'=>'required|string',
            'password'=>'required|string'
        ]);
        if(!Auth::attempt($request->all())){
            return response()->json(['message'=>'Unauthorized']);
        }
        $user = User::where($request->all)->first();
        return response()->json(['type'=>'success', 'data'=>$user, 'token'=>$user->createToken('auth-token')->plainTextToken]);
    }
}
