<?php

namespace App\Repos;

use App\Exceptions\NotFoundException;
use App\Models\Issue;
use App\Models\Label;

class IssueRepo
{
    public function getPaginatedIssues(int $app_id, $search)
    {
        $issues = Issue::with(["labels", "category"])->where([
            "app_id" => $app_id
        ]);

        if ($search) {
            $issues = $issues
                ->where("title", "like", "%$search%")
                ->orWhere("description", "like", "%$search%");
        }

        return $issues->paginate(8);
    }

    public function getIssue(int $app_id, int $id)
    {
        $issue = Issue::with(["labels", "category"])->where([
            "app_id" => $app_id,
            "id" => $id
        ])->first();

        if (!$issue) {
            throw new NotFoundException("Issue not found");
        }

        return $issue;
    }

    public function create($data)
    {
        $issue = Issue::create([
            "app_id" => $data["app_id"],
            "category_id" => $data["category_id"],
            "title" => $data["title"],
            "description" => $data["description"]
        ]);

        $user = auth()->user();

        $ids = [];

        foreach ($data["labels"] as $label) {
            $label = Label::firstOrCreate([
                "value" => $label,
                "user_id" => $user->id
            ]);

            array_push($ids, $label->id);
        }

        $issue->labels()->attach($ids);

        $issue->load("labels");

        return $issue;
    }

    public function update($data)
    {
        $id = $data["id"];
        $title = $data["title"];
        $description = $data["description"];
        $category_id = $data["category_id"];
        $solved = $data["solved"];

        Issue::find($id)->update([
            "title" => $title,
            "description" => $description,
            "category_id" => $category_id,
            "solved" => $solved
        ]);
    }

    public function destroy(int $app_id, int $issue_id)
    {
        $issue = $this->getIssue($app_id, $issue_id);
        $issue->delete();
    }

    public function getStatistics($app_id)
    {
        $total = Issue::where([
            "app_id" => $app_id
        ])->count();

        $totalSolved = Issue::where([
            "app_id" => $app_id,
            "solved" => true
        ])->count();

        $totalUnsolved = Issue::where([
            "app_id" => $app_id,
            "solved" => false
        ])->count();

        return [
            "total" => $total,
            "totalSolved" => $totalSolved,
            "totalUnsolved" => $totalUnsolved
        ];
    }
}
