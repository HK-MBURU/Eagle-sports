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
        Schema::create('race_results', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category'); // e.g., Marathon, Half Marathon, 10K, 5K
            $table->string('file_path'); // Store file path
            $table->string('file_name'); // Original file name
            $table->string('file_type'); // image, pdf, document, etc.
            $table->unsignedBigInteger('file_size')->nullable(); // File size in bytes
            $table->unsignedBigInteger('uploaded_by'); // Admin who uploaded
            $table->text('description')->nullable(); // Optional description
            $table->date('race_date')->nullable(); // When the race happened
            $table->string('location')->nullable(); // Where the race took place
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->integer('views_count')->default(0);
            $table->integer('downloads_count')->default(0);
            $table->timestamps();
            $table->softDeletes(); // For soft deleting results

            // Foreign key constraint
            $table->foreign('uploaded_by')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            // Indexes for better performance
            $table->index('category');
            $table->index('uploaded_by');
            $table->index('race_date');
            $table->index('is_published');
            $table->index('is_featured');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
