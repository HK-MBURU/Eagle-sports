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
        Schema::table('stories', function (Blueprint $table) {
            // Speeds up: WHERE status='published' AND published_date<=NOW() ORDER BY published_date DESC
            $table->index(['status', 'published_date'], 'stories_status_published_date_idx');
        });
    }

    public function down(): void
    {
        Schema::table('stories', function (Blueprint $table) {
            $table->dropIndex('stories_status_published_date_idx');
        });
    }
};
