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
        Schema::create('climate_contents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('image_url')->nullable();
            $table->string('stat_1_value'); // +2.5°C
            $table->string('stat_1_label'); // Temp increase in Rift Valley
            $table->string('stat_2_value'); // -30%
            $table->string('stat_2_label'); // Rainfall variability
            $table->string('cta_text')->default('Learn More About Our Climate Work');
            $table->string('cta_url')->default('/impact/climate');
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('climates');
    }
};
