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
       Schema::create('women_running', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title');
            $table->text('description');
            $table->enum('category', [
                'mentorship',
                'coaching',
                'advocacy',
                'featured_story'
            ])->default('mentorship');
            $table->string('image')->nullable();
            $table->string('icon')->nullable(); // For card icons
            $table->boolean('featured')->default(false);
            $table->longText('story_content')->nullable(); // Full story content
            $table->string('video_url')->nullable(); // Interview video URL
            $table->integer('order')->default(0); // Display order
            $table->boolean('status')->default(true); // Active/Inactive
            $table->timestamps();

            // Indexes
            $table->index('category');
            $table->index('featured');
            $table->index('status');
            $table->index('order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('women_runnings');
    }
};
