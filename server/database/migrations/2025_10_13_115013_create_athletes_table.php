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
        Schema::create('athletes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title'); // e.g., "Marathon World Record Holder"
            $table->text('description'); // e.g., "Climate Advocate & Foundation Founder"
            $table->text('bio'); // Full bio/story text
            $table->string('image'); // Profile image path
            $table->string('badge')->nullable(); // e.g., "Legend", "Champion"
            $table->string('badge_color')->default('#FFBF00'); // Badge background color
            $table->boolean('is_featured')->default(false);
            $table->integer('display_order')->default(0); // For ordering athletes
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('athletes');
    }
};
