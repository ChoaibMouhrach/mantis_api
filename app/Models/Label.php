<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Issue;

class Label extends Model
{
    use HasFactory;

    protected $fillable = [
        "value",
        "user_id"
    ];

    public function labels()
    {
        return $this->belongsToMany(Issue::class, "issue_label", "label_id", "issue_id");
    }
}
