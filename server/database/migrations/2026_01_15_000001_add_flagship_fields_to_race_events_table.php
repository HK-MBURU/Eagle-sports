<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('race_events', function (Blueprint $table) {
            $table->boolean('is_flagship')->default(false)->after('is_active');
            $table->string('eyebrow_text')->nullable()->after('is_flagship');
            $table->string('subtitle')->nullable()->after('eyebrow_text');
            $table->text('coverage_description')->nullable()->after('subtitle');
            $table->string('art_image_url', 500)->nullable()->after('coverage_description');
            $table->string('art_caption')->nullable()->after('art_image_url');
            $table->json('quick_links')->nullable()->after('art_caption');
        });
    }

    public function down(): void
    {
        Schema::table('race_events', function (Blueprint $table) {
            $table->dropColumn([
                'is_flagship',
                'eyebrow_text',
                'subtitle',
                'coverage_description',
                'art_image_url',
                'art_caption',
                'quick_links',
            ]);
        });
    }
};
