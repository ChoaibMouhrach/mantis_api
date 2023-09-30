<?php

namespace App\Repos;

use App\Exceptions\BadRequestException;
use App\Exceptions\NotFoundException;
use App\Models\User;

class UserRepo
{

    public function getByEmailOrThrow(string $email)
    {
        $user = $this->getByEmail($email);

        if (!$user) {
            throw new NotFoundException("User not found");
        }

        return $user;
    }

    public function getByEmail(string $email)
    {
        $user = User::where([
            "email" => $email
        ])->first();

        return $user;
    }

    public function create(string $name, string $email, string $password)
    {

        $user = $this->getByEmail($email);

        if ($user) {
            throw new BadRequestException("Email address already taken");
        }

        return User::create([
            "name" => $name,
            "email" => $email,
            "password" => bcrypt($password)
        ]);
    }
}
