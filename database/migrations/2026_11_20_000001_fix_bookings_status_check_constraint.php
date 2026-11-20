<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop the restrictive check constraint on PostgreSQL so that 'confirmed', 'Selesai', 'paid', etc. are accepted
        DB::statement("ALTER TABLE bookings DROP CONSTRAINT IF EXISTS bookings_status_check");
        
        // Ensure status column accepts varchar without check constraint violations
        try {
            DB::statement("ALTER TABLE bookings ALTER COLUMN status TYPE VARCHAR(50)");
        } catch (\Throwable $e) {
            // Already varchar or compatible
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op
    }
};
