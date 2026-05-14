<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\User;
use App\Models\Course;
use App\Models\Lpk;
use Illuminate\Support\Str;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil User pertama (student)
        $user = User::first();
        
        // Ambil Course pertama beserta relasi LPK
        $course = Course::with('lpk')->first();

        if (!$user || !$course) {
            $this->command->warn('Seeding skipped: No User or Course found in database.');
            return;
        }

        // Ambil tenant_id dari LPK terkait
        $tenantId = $course->lpk ? $course->lpk->tenant_id : null;

        if (!$tenantId) {
            $this->command->warn('Seeding skipped: Course has no associated Tenant via LPK.');
            return;
        }

        // Hapus data lama untuk reset testing
        Booking::where('user_id', $user->id)->delete();

        // Data 1: Menunggu
        Booking::create([
            'id' => (string) Str::uuid(),
            'tenant_id' => $tenantId, // Wajib diisi agar muncul di Dashboard Admin
            'user_id' => $user->id,
            'course_id' => $course->id,
            'lpk_id' => $course->lpk_id,
            'status' => 'Menunggu',
            'total_price' => $course->price ?? 150000,
            'booking_date' => now(),
        ]);

        // Data 2: Selesai
        Booking::create([
            'id' => (string) Str::uuid(),
            'tenant_id' => $tenantId,
            'user_id' => $user->id,
            'course_id' => $course->id,
            'lpk_id' => $course->lpk_id,
            'status' => 'Selesai',
            'total_price' => $course->price ?? 200000,
            'booking_date' => now()->subDays(2),
        ]);

        // Data 3: Dibatalkan
        Booking::create([
            'id' => (string) Str::uuid(),
            'tenant_id' => $tenantId,
            'user_id' => $user->id,
            'course_id' => $course->id,
            'lpk_id' => $course->lpk_id,
            'status' => 'Dibatalkan',
            'total_price' => $course->price ?? 100000,
            'booking_date' => now()->subDays(5),
        ]);

        $this->command->info('BookingSeeder: 3 dummy bookings created with tenant_id mapping.');
    }
}
