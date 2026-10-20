<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('story_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('slug', 120)->unique();
            $table->text('description')->nullable();
            $table->string('color', 7)->default('#6366f1');
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // Seed with existing hardcoded categories + Race Reports
        $categories = [
            ['name' => 'Athletics',                 'color' => '#d62835', 'sort_order' => 1],
            ['name' => 'Race Results',              'color' => '#0891b2', 'sort_order' => 2],
            ['name' => 'Race Reports',              'color' => '#7c3aed', 'sort_order' => 3],
            ['name' => 'NCAA Stories',              'color' => '#2563eb', 'sort_order' => 4],
            ['name' => 'Success Stories',           'color' => '#16a34a', 'sort_order' => 5],
            ['name' => 'Youth Empowerment',         'color' => '#f59e0b', 'sort_order' => 6],
            ['name' => 'Community Projects',        'color' => '#10b981', 'sort_order' => 7],
            ['name' => 'Climate and athletics',     'color' => '#15803d', 'sort_order' => 8],
            ['name' => 'Coaching Tips',             'color' => '#d97706', 'sort_order' => 9],
            ['name' => 'Sports Science',            'color' => '#6366f1', 'sort_order' => 10],
            ['name' => 'Athlete Health and Mindset','color' => '#db2777', 'sort_order' => 11],
        ];

        foreach ($categories as $cat) {
            DB::table('story_categories')->insert([
                'name'       => $cat['name'],
                'slug'       => Str::slug($cat['name']),
                'color'      => $cat['color'],
                'is_active'  => true,
                'sort_order' => $cat['sort_order'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('story_categories');
    }
};
