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
       Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('youtube_url');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('category', [
                'athletics',
                'football',
                'rugby',
                'basketball',
                'volleyball',
                'boxing',
                'cricket',
                'swimming',
                'martial_arts',
                'other'
            ])->default('other');
            $table->boolean('is_featured')->default(false);
            $table->foreignId('added_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            // Indexes for better performance
            $table->index('category');
            $table->index('is_featured');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
