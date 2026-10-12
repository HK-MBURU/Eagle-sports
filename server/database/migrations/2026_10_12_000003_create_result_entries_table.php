<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('result_entries', function (Blueprint $table) {
            $table->id();
            $table->string('competition');
            $table->string('event_discipline');
            $table->string('performance');
            $table->string('athlete_name')->nullable();
            $table->string('athlete_country', 10)->nullable();
            $table->string('series')->nullable();     // Diamond League, Continental Tour, Road Race
            $table->string('result_status')->default('official'); // official, pending, placeholder
            $table->date('race_date')->nullable();
            $table->string('location')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index(['series', 'is_published']);
            $table->index(['race_date', 'is_published']);
            $table->index('is_featured');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('result_entries');
    }
};
