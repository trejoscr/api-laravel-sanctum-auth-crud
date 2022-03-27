<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
  public function register(Request $request)
  {
    $request->validate([
      'name'     => 'required',
      'email'    => 'required|email|unique:users',
      'password' => 'required|confirmed',
    ]);

    $user = new User();
    $user->name     = $request->name;
    $user->email    = $request->email;
    $user->password = Hash::make($request->password);
    $user->save();

    return response()->json([
      'status' => 1,
      'msg'    => 'User created successfully!',
    ], 200);

  }

  public function login(Request $request)
  {

    $request->validate([
      'email'    => 'required|email',
      'password' => 'required',
    ]);

    $user = User::where('email', '=', $request->email)->first();

    if(isset($user->id)){

      if (Hash::check($request->password, $user->password)) {
        
        // create token
        $token = $user->createToken("auth_token")->plainTextToken;

        return response()->json([
          'status'       => 1,
          'msg'          => 'User login successfully!',
          'access_token' => $token
        ], 200);


      }else{
        return response()->json([
          'status' => 0,
          'msg'    => 'Incorrect password!',
        ], 404);
      }

    }else{
      return response()->json([
        'status' => 0,
        'msg'    => 'User no registed!',
      ], 404);
    }
    
  }

  public function userProfile()
  {
    return response()->json([
        'status' => 0,
        'msg'    => 'Profile User',
        'data'   => auth()->user()
      ]);
  }

  public function logout()
  {
    auth()->user()->tokens()->delete();
    return response()->json([
      'status' => 1,
      'msg'    => 'Logout'
    ]);
  }
}
