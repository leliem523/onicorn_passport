<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginValidation;
use App\Http\Requests\RegisterValidation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Xử lý chức năng đăng ký
    public function register(RegisterValidation $request)
    {
        $user = $request->validated();

        $user['password'] = bcrypt($user['password']);
        try {
            User::create($user);
        } catch (\Throwable $th) {
            return response()->json(["msg" => "Account already exists !!"], 422);
        }
        return response()->json(["user" => $user, "msg" => "Register succeed !!"], 202);
        
        
    }

     // Xử lý chức năng đăng nhập
     public function login(LoginValidation $request)
     {
         $user = $request->validated();
        //  $user['password'] = bcrypt($user['password']);
         if(Auth::attempt($user)) {
             $user = Auth::user();
             // Tạo mã xác thực api bằng hàm createToken nằm trong hàm Auth::user()
             $token = $user->createToken('api-application')->accessToken;
             return response(["user" => Auth::user(), 'token' => $token, 'msg' => 'Login succeed !!'], 202);
         }
         return response()->json(['msg' => 'Login failed !!'], 422);
     }

     public function getUser()
     {
         if(Auth::check()) {
             return response()->json(["user" => Auth::user()], 200);
         }
         return response()->json(["msg" => "Cannot find any user"], 202);
     }
}
