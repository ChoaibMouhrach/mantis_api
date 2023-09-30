<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        $categories = Category::where([
            "user_id" => $user->id
        ])->paginate(8);

        return response($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $user = auth()->user();
        $validated = $request->validated();
        $value = $validated["value"];

        $category = Category::where([
            "user_id" => $user->id,
            "value" => $value
        ])->first();

        if ($category) {
            return response([
                "message" => "Category already exists"
            ], 400);
        }

        $category = Category::create([
            "value" => $validated["value"],
            "user_id" => $user->id
        ]);

        return response($category);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = auth()->user();

        $category = Category::where([
            "id" => $id,
            "user_id" => $user->id
        ])->first();

        if (!$category) {
            return response([
                "message" => "Category not found"
            ], 404);
        }

        return response($category, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $user = auth()->user();
        $validated = $request->validated();

        $value = $validated["value"];

        $category = Category::where([
            "id" => $id,
            "user_id" => $user->id
        ])->first();

        if (!$category) {
            return response([
                "message" => "Category not found"
            ], 404);
        }

        $category = Category::where([
            "user_id" => $user->id,
            "value" => $value
        ])->first();

        if ($category && $category->id !== $id) {
            return response([
                "message" => "Value is already taken"
            ], 400);
        }

        $category = Category::where([
            "id" => $id,
        ])->update([
            "value" => $validated["value"]
        ]);

        return response($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = auth()->user();

        $category = Category::where([
            "id" => $id,
            "user_id" => $user->id
        ]);

        if (!$category) {
            return response([
                "message" => "Category not found"
            ], 404);
        }

        $category->delete();

        return response(true);
    }
}
