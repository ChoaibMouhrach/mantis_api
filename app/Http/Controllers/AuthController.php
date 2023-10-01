<?php

namespace App\Http\Controllers;

use App\Exceptions\BadRequestException;
use App\Http\Requests\SignInUserRequest;
use App\Http\Requests\SignUpUserRequest;
use App\Repos\UserRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public UserRepo $repo;

    public function __construct(UserRepo $repo)
    {
        $this->repo = $repo;
    }

    public function signIn(SignInUserRequest $request)
    {
        $validated = $request->validated();
        $email = $validated["email"];
        $password = $validated["password"];

        $user = $this->repo->getByEmailOrThrow($email);

        if (!Hash::check($password, $user->password)) {
            throw new BadRequestException("Password is not valid");
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

        $user = $this->repo->create($name, $email, $password);

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

    public function getProfile()
    {
        return response()->json(auth()->user());
    }
}
