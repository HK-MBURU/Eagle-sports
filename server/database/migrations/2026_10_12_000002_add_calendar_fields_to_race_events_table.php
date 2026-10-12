<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('race_events', function (Blueprint $table) {
            $table->string('cal_top', 20)->nullable()->after('quick_links');
            $table->string('cal_bottom', 20)->nullable()->after('cal_top');
            $table->string('cal_note', 500)->nullable()->after('cal_bottom');
            $table->boolean('is_calendar_event')->default(false)->after('cal_note');
        });
    }

    public function down(): void
    {
        Schema::table('race_events', function (Blueprint $table) {
            $table->dropColumn(['cal_top', 'cal_bottom', 'cal_note', 'is_calendar_event']);
        });
    }
};
