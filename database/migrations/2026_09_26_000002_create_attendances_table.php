<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignUuid('schedule_id')->nullable()->constrained('course_schedules')->nullOnDelete();
            $table->foreignUuid('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('session_date');
            $table->string('session_name')->default('Pertemuan 1');
            $table->string('status')->default('present'); // present, sick, excused, absent
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['booking_id', 'session_date', 'session_name'], 'unique_booking_session');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
