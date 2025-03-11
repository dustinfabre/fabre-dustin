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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('day_of_week')->index();
            $table->time('open_time');
            $table->time('close_time');
            $table->time('lunch_start');
            $table->time('lunch_end');
            $table->boolean('every_other_week')->default(false);
            $table->date('eow_start_date')->nullable();
            $table->timestamps();

            // create a composite index for day_of_week, open_time, and close_time as they are frequently queried together
            $table->index(['day_of_week', 'open_time', 'close_time']);
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
