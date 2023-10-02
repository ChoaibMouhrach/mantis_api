<?php

namespace App\Repos;

use App\Exceptions\NotFoundException;
use App\Models\Category;

class CategoryRepo
{
    public function getPaginatedCategories($search)
    {
        $user = auth()->user();

        $categories = Category::where([
            "user_id" => $user->id
        ]);

        if($search){
            $categories = $categories->where("value", "like", "%$search%");
        }

        return $categories->paginate(8);
    }

    public function getCategory(int $id)
    {
        $user = auth()->user();

        $category = Category::where([
            "user_id" => $user->id,
            "id" => $id
        ])->first();

        if (!$category) {
            throw new NotFoundException("Category not found");
        }

        return $category;
    }

    public function getCategoryByValue(string $value)
    {
        $user = auth()->user();

        $category = Category::where([
            "user_id" => $user->id,
            "value" => $value
        ])->first();

        return $category;
    }

    public function create($data)
    {
        $user = auth()->user();
        $value = $data["value"];

        $category = Category::create([
            "value" => $value,
            "user_id" => $user->id
        ]);

        return $category;
    }

    public function update(int $id, string $value)
    {
        $category = $this->getCategory($id);

        $category->update([
            "value" => $value
        ]);

        return $category;
    }

    public function destroy(int $id)
    {
        $category = $this->getCategory($id);
        $category->delete();
        return $category;
    }
}
