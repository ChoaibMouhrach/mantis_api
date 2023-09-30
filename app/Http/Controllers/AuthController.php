<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignInUserRequest;
use App\Http\Requests\SignUpUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function signIn(SignInUserRequest $request)
    {
        $validated = $request->validated();
        $email = $validated["email"];
        $password = $validated["password"];

        $user = User::where(["email" => $email])->first();

        if (!$user) {
            return response(["message" => "Email Address does not exists on our database"]);
        }

        if (!Hash::check($password, $user->password)) {
            return response([
                "message" => "Password is not valid"
            ], 400);
        }

        $token = $user->createToken("Auth")->plainTextToken;

        return response([
            "user" => $user,
            "token" => $token
        ]);
    }

    public function signUp(SignUpUserRequest $request)
    {
        $validated = $request->validated();

        $name = $validated["name"];
        $email = $validated["email"];
        $password = $validated["password"];

        $user = User::where(["email" => $email])->first();

        if ($user) {
            return response([
                "message" => "Email address already taken"
            ]);
        }

        $user = User::create([
            "name" => $name,
            "email" => $email,
            "password" => bcrypt($password)
        ]);

        $token = $user->createToken("auth")->plainTextToken;

        return response([
            "user" => $user,
            "token" => $token
        ]);
    }

    public function signOut(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response(["message" => "See you soon"]);
    }
}
