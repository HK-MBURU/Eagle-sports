<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->string('category', 120)->default('other')->change();
        });
    }

    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->enum('category', [
                'athletics', 'football', 'rugby', 'basketball', 'volleyball',
                'boxing', 'cricket', 'swimming', 'martial_arts', 'other',
            ])->default('other')->change();
        });
    }
};
