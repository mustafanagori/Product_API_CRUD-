<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\PersonalAccessTokenFactory;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{

         // --------- register User Through API  ------//
         public function register(Request $request) {
            $rules = [
                'name' => 'required|string|max:255|min:3',
                'contact' => 'required|string|max:11|min:11|unique:users,contact',
                'address' => 'required|string|max:255|min:5',
                'password' => 'required|string|max:255|min:6',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }
            $user = new User();
            $user->name = $request->name;
            $user->contact = $request->contact;
            $user->address = $request->address;
            $user->password = Hash::make($request->password);
            $user->save();
        
            // Create a new access token using Passport
            $token = $user->createToken('mytoken')->plainTextToken;
        
            // Store the access token in the user's 'access_token' field
            $user->remember_token = $token;
            $user->save();
        
            return [
                'user' => $user,
                'token' => $token
            ];
        }
        

    
    // --------- login user though contact and password ------//

    public function login(Request $request){
        $request->validate([
            'contact' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('contact', $request->contact)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'contact' => ['The provided credentials are incorrect.'],
            ]);
        }

        // return $user->createToken($request->contact)->plainTextToken;
        return [
            'token' =>$user->remember_token,
            'user' => $user,
            'message' => 'Successfully logged in!'
        ];

    }

    

    // --------- update user detail name and address ------//

    public function updateUser(Request $request , $id){
        
        $rules = [
            'name' => 'required|string|max:255|min:3',
            'address' => 'required|string|max:255|min:5',
        ];

        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $user = User::find($id);
        if (!$user) {
            return response(['message' => 'User not found'], 404);
        }
        if($request->name)
        {
        $user->name = $request->name;
        }
    
        if($request->address)
        {
        $user->address = $request->address;
        }
        $user->save();
        return $user;
    }
}
