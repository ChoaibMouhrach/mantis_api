<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('issue_lable', function (Blueprint $table) {
            $table->id();
            $table->foreignId("issue_id")->constrained("issues", "id")->onDelete("cascade");
            $table->foreignId("label_id")->constrained("labels", "id")->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issue_lable');
    }
};
