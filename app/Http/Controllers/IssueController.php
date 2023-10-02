<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIssueRequest;
use App\Http\Requests\UpdateIssueRequest;
use App\Models\Label;
// repos
use App\Repos\AppRepo;
use App\Repos\CategoryRepo;
use App\Repos\IssueRepo;
use Illuminate\Http\Request;

class IssueController extends Controller
{

    public IssueRepo $repo;
    public AppRepo $appRepo;
    public CategoryRepo $categoryRepo;

    public function __construct(IssueRepo $repo, AppRepo $appRepo, CategoryRepo $categoryRepo)
    {
        $this->repo = $repo;
        $this->appRepo = $appRepo;
        $this->categoryRepo = $categoryRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request,  $id)
    {
        $search = $request->input("search");
        $app = $this->appRepo->getApp($id);

        $issues = $this->repo->getPaginatedIssues($app->id, $search);

        return response()->json($issues);
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
        $labels = $validated["labels"];

        $app = $this->appRepo->getApp($app_id);

        $category = $this->categoryRepo->getCategory($category_id);

        $issue = $this->repo->create([
            "app_id" => $app->id,
            "category_id" => $category->id,
            "title" => $title,
            "description" => $description,
            "labels" => $labels
        ]);

        return response()->json($issue, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($app_id, $issue_id)
    {
        $app = $this->appRepo->getApp($app_id);

        $issue = $this->repo->getIssue($app->id, $issue_id);

        return response()->json($issue);
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

        $app = $this->appRepo->getApp($app_id);

        $category = $this->categoryRepo->getCategory($category_id);

        $issue = $this->repo->getIssue($app->id, $issue_id);

        $this->repo->update([
            "id" => $issue->id,
            "title" => $title,
            "description" => $description,
            "category_id" => $category->id
        ]);

        return response()->json($issue);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($app_id, $issue_id)
    {
        $app = $this->appRepo->getApp($app_id);

        $this->repo->destroy($app->id, $issue_id);

        return response()->json(true);
    }
}
