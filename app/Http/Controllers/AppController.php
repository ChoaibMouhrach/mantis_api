<?php

namespace App\Http\Controllers;

use App\Exceptions\BadRequestException;
use App\Http\Requests\StoreAppRequest;
use App\Http\Requests\UpdateAppRequest;
use App\Repos\AppRepo;
use Illuminate\Http\Request;

class AppController extends Controller
{

    public AppRepo $repo;

    public function __construct(AppRepo $repository)
    {
        $this->repo = $repository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input("search");

        $apps = $this->repo->getPaginatedApps(
            $search
        );

        return response()->json($apps);
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

        $app = $this->repo->getAppByName($name);

        if ($app) {
            throw new BadRequestException("App already exists");
        }

        $app = $this->repo->create([
            "name" => $name,
            "description" => $description,
            "user_id" => $user->id
        ]);

        return response()->json($app, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $app = $this->repo->getApp($id);
        return response()->json($app);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAppRequest $request, $id)
    {
        $validated = $request->validated();
        $name = $validated["name"];
        $description = $validated["description"] ?? null;

        $app = $this->repo->update($id, [
            "name" => $name,
            "description" => $description
        ]);

        return response()->json($app);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->repo->destroy($id);
        return response()->json(true);
    }
}
