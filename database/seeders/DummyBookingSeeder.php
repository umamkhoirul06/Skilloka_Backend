<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Booking;
use App\Models\CourseSchedule;
use App\Models\Course;
use App\Models\Tenant;
use Illuminate\Support\Str;

class DummyBookingSeeder extends Seeder
{
    public function run()
    {
        // Cari user terakhir (user yang baru login via OTP di Mobile)
        $user = User::latest()->first();
        
        if (!$user) {
            $this->command->info('User tidak ditemukan. Silakan login di mobile terlebih dahulu.');
            return;
        }

        // Ambil sembarang jadwal kursus
        $schedule = CourseSchedule::with('course')->first();
        
        if (!$schedule) {
            $this->command->info('CourseSchedule kosong. Seeder memerlukan data master kursus.');
            return;
        }

        // Dummy 1: Menunggu Pembayaran
        Booking::create([
            'id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'tenant_id' => $schedule->course->tenant_id,
            'schedule_id' => $schedule->id,
            'code' => 'BKG-' . strtoupper(Str::random(6)),
            'status' => 'pending',
            'amount' => $schedule->course->price ?? 150000,
            'created_at' => now()->subDays(1),
            'updated_at' => now()->subDays(1),
        ]);

        // Dummy 2: Aktif (Sudah Dibayar)
        Booking::create([
            'id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'tenant_id' => $schedule->course->tenant_id,
            'schedule_id' => $schedule->id,
            'code' => 'BKG-' . strtoupper(Str::random(6)),
            'status' => 'paid',
            'amount' => $schedule->course->price ?? 200000,
            'created_at' => now()->subDays(3),
            'updated_at' => now()->subDays(3),
        ]);

        // Dummy 3: Selesai
        Booking::create([
            'id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'tenant_id' => $schedule->course->tenant_id,
            'schedule_id' => $schedule->id,
            'code' => 'BKG-' . strtoupper(Str::random(6)),
            'status' => 'completed',
            'amount' => $schedule->course->price ?? 350000,
            'created_at' => now()->subDays(10),
            'updated_at' => now()->subDays(5),
        ]);

        $this->command->info("3 Dummy Bookings berhasil di-seed untuk user: {$user->name}");
    }
}
