<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\requests\RegisterUser;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LogUserRequest;


class UserController extends Controller
{
    

    public function register(RegisterUser $request){
        try{
            $user = new User();

            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = $request->password;

        $user->password = Hash::make($request->password, [
            'rounds' => 12
        ]);

        $user->save();
        return response()->json([
            'status_code' => 200,
            'status_message' => 'Ls',
            'user' => $user
        ]);
        }catch(Exception $e){
           return response()->json($e);  
        }
        
    }

    public function login(LogUserRequest $request){
        if(auth()->attempt($request->only(['email', 'password']))){
            $user = auth()->user();

            $token = $user->createToken('MA_CLE_SECRETE_VISIBLE_UNIQUEMENT_AU_BACKEND')->plainTextToken;

            return response()->json([
                'status_code' => 200,
                'status_message' => 'User connected',
                'user' => $user,
                'token' => $token

            ]);
        }else{
            return response()->json([
                'status_code' => 200,
                'status_message' => 'User is not connected',
                'user' => $user
            ]);
        }
    }
}
