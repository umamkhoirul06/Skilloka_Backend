<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('course_schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('course_id')->constrained('courses')->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->time('daily_start');
            $table->time('daily_end');
            $table->integer('max_capacity');
            $table->string('days_of_week'); // e.g. "Mon,Tue"
            $table->enum('status', ['open', 'closed', 'cancelled'])->default('open');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_schedules');
    }
};
