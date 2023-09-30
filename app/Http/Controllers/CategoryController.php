<?php

namespace App\Http\Controllers;

use App\Exceptions\BadRequestException;
use App\Exceptions\NotFoundException;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Repos\CategoryRepo;

class CategoryController extends Controller
{

    public CategoryRepo $repo;

    public function __construct(CategoryRepo $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = $this->repo->getPaginatedCategories();
        return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $user = auth()->user();
        $validated = $request->validated();
        $value = $validated["value"];

        $category = $this->repo->getCategoryByValue($value);

        if ($category) {
            throw new BadRequestException("Category already exists");
        }

        $category = $this->repo->create([
            "value" => $validated["value"],
            "user_id" => $user->id
        ]);

        return response()->json($category, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = $this->repo->getCategory($id);
        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $validated = $request->validated();
        $value = $validated["value"];

        $category = $this->repo->getCategory($id);

        $category = $this->repo->getCategoryByValue($value);

        if ($category && $category->id !== $id) {
            throw new BadRequestException("Value is already taken");
        }

        $category = $this->repo->update($id, $validated["value"]);

        return response()->json($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->repo->destroy($id);
        return response()->json($id);
    }
}
