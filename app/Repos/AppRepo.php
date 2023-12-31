<?php

namespace App\Repos;

use App\Exceptions\NotFoundException;
use App\Models\App;

class AppRepo
{

    public function getPaginatedApps($search)
    {
        $user = auth()->user();

        $apps = App::where([
            "user_id" => $user->id
        ]);

        if ($search) {
            $apps = $apps->where("name", "like", "%$search%");
        }

        return $apps->paginate(8);
    }

    protected function getAppBase($where)
    {
        $user = auth()->user();

        $app = App::where([
            "user_id" => $user->id,
            ...$where
        ])->first();

        if (!$app) {
            throw new NotFoundException("App not found");
        }

        return $app;
    }

    public function getAppByName(string $name)
    {
        $user = auth()->user();

        return App::where([
            "user_id" => $user->id,
            "name" => $name
        ])->first();
    }

    public function getAppByNameOrThrow(string $name)
    {
        return $this->getAppBase(["name" => $name]);
    }

    public function getApp(int $id)
    {
        return $this->getAppBase(["id" => $id]);
    }

    public function create($data)
    {
        return App::create([
            "name" => $data["name"],
            "description" => $data["description"],
            "user_id" => $data["user_id"]
        ]);
    }

    public function update(int $id, $data)
    {
        $app = $this->getApp($id);

        $app->name = $data["name"];
        $app->description = $data["description"];

        $app->save();

        return $app;
    }

    public function destroy(int $id)
    {
        $app = $this->getApp($id);
        return $app->delete();
    }
}
