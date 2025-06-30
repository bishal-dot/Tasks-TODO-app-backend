<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller{
    public function register (Request $request){
        $validate = Validator::make($request->all(),[
            'fname'    => 'required|string|max:100',
            'lname'    => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password'  => 'required|string|min:8',
        ]);
        // validation
        if($validate->fails()){
            $Errors = $validate->errors();

            $ErrMessage = "";
            if($Errors->has('email')) $ErrMessage = 'The email has already been taken.';
            else $ErrMessage = 'Validation error';

            return response()->json([
                'error'   => true,
                'reason'  => $ErrMessage,
                'response'  => null
            ]);
        }

        // resgister the user
        $User = User::create($request->all());

        // return the new user
        return response()->json([
            'error'     => false,
            'reason'    =>'listed',
            'response'  => $User
        ]);
    }

    public function login(Request $request){
        $Validate = Validator::make($request->all(),[
            'email'     => 'required|email',
            'password'  => 'required|string|min:8'
        ]);

         // validation
        if ($Validate->fails()) {
            return response()->json([
                'error'     => true,
                'reason'    => 'Validation error',
                'response'  => null
            ]);
        }

        $User = User::where('email', $request->email)->first();
        if (!$User || !Hash::check($request->password, $User->password)) {
            return response()->json([
                'error'     => true,
                'reason'    => "Email and Password didn't matched",
                'response'  => null
            ]);
        }
        
        // create token and return
        $token = $User->createToken('user')->plainTextToken;

        return response()->json([
           'error'     => false,
            'reason'    => 'success',
            'response'  => array(
                'user' => $User,
                'token'=> $token
            )
        ]); 
    }

    public function logout (Request $request){
        $request->user()->tokens()->delete();
        return response()->json([
            'error'     => false,
            'reason'    => 'success',
            'response'  => null
        ]);
    }
}
