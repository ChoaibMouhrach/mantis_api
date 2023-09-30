<?php

namespace App\Http\Controllers;

use App\Exceptions\NotFoundException;
use App\Http\Requests\StoreIssueRequest;
use App\Http\Requests\UpdateIssueRequest;
use App\Models\App;
use App\Models\Issue;

class IssueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $user = auth()->user();

        $app = App::where([
            "user_id" => $user->id,
            "id" => $id
        ])->first();

        if (!$app) {
            throw new NotFoundException("App not found");
        }

        $issues = Issue::where([
            "app_id" => $app->id
        ])->paginate(8);

        return response($issues);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIssueRequest $request, $app_id)
    {
        $validated = $request->validated();
        $category_id = $validated["category_id"];
        $title = $validated["title"];
        $description = $validated["description"] ?? null;

        $user = auth()->user();

        $app = App::where([
            "user_id" => $user->id,
            "id" => $app_id
        ])->first();

        if (!$app) {
            throw new NotFoundException("App not found");
        }

        $category = App::where([
            "user_id" => $user->id,
            "id" => $app_id
        ])->first();

        if (!$category) {
            throw new NotFoundException("Category not found");
        }

        $issue = Issue::create([
            "app_id" => $app_id,
            "category_id" => $category_id,
            "title" => $title,
            "description" => $description
        ]);

        return response($issue, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($app_id, $issue_id)
    {
        $user = auth()->user();

        $app = App::where([
            "user_id" => $user->id,
            "id" => $app_id
        ])->first();

        if (!$app) {
            throw new NotFoundException("App not found");
        }

        $issue = Issue::where([
            "app_id" => $app->id,
            "id" => $issue_id
        ])->first();

        if (!$issue) {
            throw new NotFoundException("Issue not found");
        }

        return response($issue);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIssueRequest $request, $app_id, $issue_id)
    {
        $validated = $request->validated();

        $title = $validated["title"];
        $description = $validated["description"] ?? null;
        $category_id = $validated["category_id"];

        $user = auth()->user();

        $app = App::where([
            "id" => $app_id,
            "user_id" => $user->id
        ])->first();

        if (!$app) {
            throw new NotFoundException("App not found");
        }

        $category = App::where([
            "id" => $category_id,
            "user_id" => $user->id
        ])->first();

        if (!$category) {
            throw new NotFoundException("Category not found");
        }

        $issue = Issue::where([
            "id" => $issue_id,
            "app_id" => $app_id
        ])->first();

        if (!$issue) {
            throw new NotFoundException("Issue not found");
        }

        $issue->update([
            "title" => $title,
            "description" => $description,
            "category_id" => $category_id
        ]);

        return response($issue);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($app_id, $issue_id)
    {
        $user = auth()->user();

        $app = App::where([
            "id" => $app_id,
            "user_id" => $user->id
        ])->first();

        if (!$app) {
            throw new NotFoundException("App not found");
        }

        $issue = Issue::where([
            "app_id" => $app->id,
            "id" => $issue_id
        ])->first();

        if (!$issue) {
            throw new NotFoundException("Issue not found");
        }

        $issue->delete();

        return response(true);
    }
}
