<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('caption')->nullable();
            $table->string('alt_text')->nullable();
            $table->string('image_path', 500)->nullable();  // relative storage path
            $table->string('category', 100)->nullable();
            $table->string('source_type', 50)->default('upload'); // upload|story|women_running|race_event
            $table->unsignedBigInteger('source_id')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->enum('status', ['published', 'draft'])->default('published');
            $table->unsignedBigInteger('added_by')->nullable();
            $table->timestamps();

            $table->foreign('added_by')->references('id')->on('users')->nullOnDelete();
            $table->index(['status', 'sort_order']);
            $table->index(['source_type', 'source_id']);
            $table->index('category');
            $table->index('is_featured');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};
