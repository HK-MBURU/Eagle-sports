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
        Schema::create('stories', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('excerpt'); // Short description shown on cards
            $table->longText('content'); // Full story content
            $table->string('image'); // Featured image path
            $table->string('category'); // Career Transitions, Leadership, Community Impact, Environment
            $table->boolean('is_featured')->default(false); // For "Featured Story" badge
            $table->string('author_name'); // e.g., "David Kimani"
            $table->integer('read_time'); // In minutes, e.g., 8
            $table->date('published_date'); // e.g., "March 15, 2024"
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->string('slug')->unique(); // For URLs
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stories');
    }
};
