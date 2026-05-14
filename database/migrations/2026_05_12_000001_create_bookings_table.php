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
        // Drop existing table with CASCADE to handle foreign key dependencies (PostgreSQL)
        \Illuminate\Support\Facades\DB::statement('DROP TABLE IF EXISTS bookings CASCADE');

        Schema::create('bookings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            // Relasi ke User (UUID)
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            
            // Relasi ke Course (UUID)
            $table->foreignUuid('course_id')->constrained('courses')->onDelete('cascade');
            
            // Relasi ke LPK (UUID)
            $table->foreignUuid('lpk_id')->nullable()->constrained('lpks')->onDelete('cascade');
            
            // Status sesuai permintaan user
            $table->enum('status', ['Menunggu', 'Selesai', 'Dibatalkan'])->default('Menunggu');
            
            $table->decimal('total_price', 12, 2);
            $table->timestamp('booking_date')->useCurrent();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
