<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLabelRequest;
use App\Http\Requests\UpdateLabelRequest;
use App\Models\Label;
use App\Repos\LabelRepo;

class LabelController extends Controller
{

    public LabelRepo $repo;

    public function __construct(LabelRepo $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $labels = $this->repo->getPaginatedLabels();
        return response()->json($labels);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLabelRequest $request)
    {
        $validated = $request->validated();
        $value = $validated["value"];
        $label = $this->repo->create($value);
        return response()->json($label, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return $this->repo->findOrThrow($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLabelRequest $request, $id)
    {
        $validated = $request->validated();
        $value = $validated["value"];
        $label = $this->repo->update($id, $value);
        return response()->json($label);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $this->repo->destroy($id);
        return response()->json(true);
    }
}
