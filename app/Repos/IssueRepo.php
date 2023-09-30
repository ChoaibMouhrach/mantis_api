<?php

namespace App\Repos;

use App\Exceptions\NotFoundException;
use App\Models\Issue;

class IssueRepo
{
    public function getPaginatedIssues(int $app_id)
    {
        return Issue::where([
            "app_id" => $app_id
        ])->paginate(8);
    }

    public function getIssue(int $app_id, int $id)
    {
        $issue = Issue::where([
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
        return Issue::create([
            "app_id" => $data["app_id"],
            "category_id" => $data["category_id"],
            "title" => $data["title"],
            "description" => $data["description"]
        ]);
    }

    public function update($data)
    {
        $id = $data["id"];
        $title = $data["title"];
        $description = $data["description"];
        $category_id = $data["category_id"];

        Issue::find($id)->update([
            "title" => $title,
            "description" => $description,
            "category_id" => $category_id
        ]);
    }

    public function destroy(int $app_id, int $issue_id)
    {
        $issue = $this->getIssue($app_id, $issue_id);
        $issue->delete();
    }
}
