<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Label;

class Issue extends Model
{
    use HasFactory;

    protected $fillable = [
        "solved",
        "app_id",
        "category_id",
        "title",
        "description"
    ];

    public function labels()
    {
        return $this->belongsToMany(Label::class, "issue_label", "issue_id", "label_id");
    }

    public function category()
    {
        return $this->belongsTo(Category::class, "category_id", "id", "categories");
    }
}
