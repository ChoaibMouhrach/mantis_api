<?php

namespace App\Http\Controllers;

use App\Exceptions\BadRequestException;
use App\Exceptions\NotFoundException;
use App\Http\Requests\StoreAppRequest;
use App\Http\Requests\UpdateAppRequest;
use App\Models\App;

class AppController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        $apps = App::where([
            "user_id" => $user->id
        ])->paginate(8);

        return response($apps);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAppRequest $request)
    {
        $user = auth()->user();
        $validated = $request->validated();

        $name = $validated["name"];
        $description = $validated["description"] ?? null;

        $app = App::where([
            "user_id" => $user->id,
            "name" => $name
        ])->first();

        if ($app) {
            throw new BadRequestException("Name is already taken");
        }

        $app = App::create([
            "name" => $name,
            "description" => $description,
            "user_id" => $user->id
        ]);

        return response($app);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = auth()->user();

        $app = App::where([
            "user_id" => $user->id,
            "id" => $id
        ])->first();

        return response($app);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAppRequest $request, $id)
    {
        $user = auth()->user();
        $validated = $request->validated();

        $app = App::where([
            "user_id" => $user->id,
            "id" => $id
        ])->first();

        if (!$app) {
            throw new NotFoundException("App not found");
        }

        $app->update($validated);

        return response($app);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = auth()->user();

        $app = App::where([
            "id" => $id,
            "user_id" => $user->id
        ])->first();

        if (!$app) {
            throw new NotFoundException("App not found");
        }

        $app->delete();

        return response(true);
    }
}
