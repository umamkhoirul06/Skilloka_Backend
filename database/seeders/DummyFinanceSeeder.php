<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Lpk;
use App\Models\Category;
use App\Models\Course;
use App\Models\CourseSchedule;
use App\Models\Booking;
use App\Models\Payment;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DummyFinanceSeeder extends Seeder
{
    public function run()
    {
        // 1. Pastikan role student ada
        $studentRole = Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

        // 2. Ambil Tenants LPK
        $tenants = Tenant::with('lpk')->get();
        if ($tenants->isEmpty()) {
            $this->command->warn('Tenant kosong. Jalankan SuperAdminSeeder terlebih dahulu.');
            return;
        }

        // 3. Ambil Kategori
        $categories = Category::all();

        // 4. Data Kursus Pilot Indramayu
        $coursesData = [
            [
                'title' => 'Teknisi Otomotif & Servis Injeksi',
                'description' => 'Program pelatihan kejuruan mekanik sepeda motor injeksi berstandar industri dengan sertifikasi BNSP resmi.',
                'level' => 'intermediate',
                'price' => 1250000,
                'duration_hours' => 60,
                'max_participants' => 20,
                'category_slug' => 'teknik-otomotif-manufaktur',
                'tenant_domain' => 'merahputih',
            ],
            [
                'title' => 'Operator Mesin Bubut & CNC Presisi',
                'description' => 'Pelatihan operator permesinan manufaktur bubut konvensional dan pemrograman CNC siap kerja industri.',
                'level' => 'advanced',
                'price' => 1800000,
                'duration_hours' => 80,
                'max_participants' => 15,
                'category_slug' => 'teknik-otomotif-manufaktur',
                'tenant_domain' => 'merahputih',
            ],
            [
                'title' => 'Web Programming & Fullstack Modern',
                'description' => 'Kursus intensif coding web, REST API, dan database untuk mencetak web developer andal dari Indramayu.',
                'level' => 'beginner',
                'price' => 1500000,
                'duration_hours' => 50,
                'max_participants' => 25,
                'category_slug' => 'teknologi-digital',
                'tenant_domain' => 'technoskill',
            ],
            [
                'title' => 'Desain Grafis Komersial & Branding UMKM',
                'description' => 'Kuasai Adobe Photoshop, Illustrator, dan pembuatan konten visual kreatif untuk kebutuhan promosi digital.',
                'level' => 'beginner',
                'price' => 850000,
                'duration_hours' => 40,
                'max_participants' => 30,
                'category_slug' => 'teknologi-digital',
                'tenant_domain' => 'technoskill',
            ],
            [
                'title' => 'Bahasa Jepang Vokasi & Tokutei Ginou',
                'description' => 'Pelatihan bahasa Jepang intensif level N4 dan etika kerja industri Jepang untuk program penempatan luar negeri.',
                'level' => 'intermediate',
                'price' => 2500000,
                'duration_hours' => 120,
                'max_participants' => 20,
                'category_slug' => 'bahasa-asing',
                'tenant_domain' => 'globalenglish',
            ],
            [
                'title' => 'Bahasa Inggris Kapal Pesiar & Hospitality',
                'description' => 'Pelatihan komunikasi bahasa Inggris praktis untuk persiapan karir perhotelan internasional dan kapal pesiar.',
                'level' => 'intermediate',
                'price' => 2000000,
                'duration_hours' => 90,
                'max_participants' => 20,
                'category_slug' => 'bahasa-asing',
                'tenant_domain' => 'globalenglish',
            ],
        ];

        $createdCourses = [];
        foreach ($coursesData as $c) {
            $tenant = Tenant::where('domain', $c['tenant_domain'])->first() ?? $tenants->first();
            $lpk = Lpk::where('tenant_id', $tenant->id)->first();
            $category = Category::where('slug', $c['category_slug'])->first() ?? $categories->first();

            $course = Course::updateOrCreate(
                ['title' => $c['title'], 'tenant_id' => $tenant->id],
                [
                    'id' => (string) Str::uuid(),
                    'slug' => Str::slug($c['title']),
                    'lpk_id' => $lpk ? $lpk->id : null,
                    'category_id' => $category ? $category->id : null,
                    'description' => $c['description'],
                    'level' => $c['level'],
                    'price' => $c['price'],
                    'duration_hours' => $c['duration_hours'],
                    'max_participants' => $c['max_participants'],
                    'is_active' => true,
                ]
            );

            // Buat Course Schedule
            $schedule = CourseSchedule::firstOrCreate(
                ['course_id' => $course->id],
                [
                    'id' => (string) Str::uuid(),
                    'start_date' => now()->addDays(7)->toDateString(),
                    'end_date' => now()->addDays(37)->toDateString(),
                    'days_of_week' => 'Senin, Rabu, Jumat',
                    'daily_start' => '08:30:00',
                    'daily_end' => '12:00:00',
                    'max_capacity' => $course->max_participants,
                    'status' => 'open',
                ]
            );

            $createdCourses[] = ['course' => $course, 'schedule' => $schedule, 'tenant' => $tenant, 'lpk' => $lpk];
        }

        // 5. Data Siswa
        $studentsData = [
            ['name' => 'Budi Santoso', 'email' => 'budi.santoso@gmail.com', 'phone' => '081234567001'],
            ['name' => 'Siti Nurhaliza', 'email' => 'siti.nurhaliza@gmail.com', 'phone' => '081234567002'],
            ['name' => 'Ahmad Fauzi', 'email' => 'ahmad.fauzi@gmail.com', 'phone' => '081234567003'],
            ['name' => 'Dewi Lestari', 'email' => 'dewi.lestari@gmail.com', 'phone' => '081234567004'],
            ['name' => 'Rizky Ramadhan', 'email' => 'rizky.ramadhan@gmail.com', 'phone' => '081234567005'],
            ['name' => 'Mega Utami', 'email' => 'mega.utami@gmail.com', 'phone' => '081234567006'],
            ['name' => 'Dwi Prasetyo', 'email' => 'dwi.prasetyo@gmail.com', 'phone' => '081234567007'],
            ['name' => 'Indah Permatasari', 'email' => 'indah.permata@gmail.com', 'phone' => '081234567008'],
            ['name' => 'Fajar Pratama', 'email' => 'fajar.pratama@gmail.com', 'phone' => '081234567009'],
            ['name' => 'Rina Sugiarto', 'email' => 'rina.sugiarto@gmail.com', 'phone' => '081234567010'],
        ];

        $paymentMethods = [
            ['method' => 'qris', 'provider' => 'Midtrans QRIS'],
            ['method' => 'bank_transfer', 'provider' => 'BCA Virtual Account'],
            ['method' => 'bank_transfer', 'provider' => 'Mandiri Virtual Account'],
            ['method' => 'gopay', 'provider' => 'GoPay / GoTo'],
            ['method' => 'bank_transfer', 'provider' => 'BNI Virtual Account'],
        ];

        // 6. Seed Bookings & Payments dengan tanggal bervariasi (historis 3 bulan)
        foreach ($studentsData as $idx => $s) {
            $student = User::updateOrCreate(
                ['email' => $s['email']],
                [
                    'name' => $s['name'],
                    'phone' => $s['phone'],
                    'password' => Hash::make('password123'),
                    'status' => 'active',
                ]
            );
            $student->syncRoles(['student', 'user']);

            // Pilih salah satu kursus
            $chosen = $createdCourses[$idx % count($createdCourses)];
            $course = $chosen['course'];
            $schedule = $chosen['schedule'];
            $tenant = $chosen['tenant'];
            $lpk = $chosen['lpk'];

            $dateOffsetDays = ($idx * 7) % 75; // Rentang 75 hari terakhir
            $trxDate = now()->subDays($dateOffsetDays);

            // Status transaksi: mayoritas success, 2 pending, 1 failed
            if ($idx === 4) {
                $status = 'pending';
                $bookingStatus = 'pending';
            } elseif ($idx === 8) {
                $status = 'failed';
                $bookingStatus = 'cancelled';
            } else {
                $status = 'success';
                $bookingStatus = 'paid';
            }

            $booking = Booking::create([
                'id' => (string) Str::uuid(),
                'user_id' => $student->id,
                'tenant_id' => $tenant->id,
                'lpk_id' => $lpk ? $lpk->id : null,
                'course_id' => $course->id,
                'schedule_id' => $schedule->id,
                'total_price' => $course->price,
                'booking_date' => $trxDate,
                'status' => $status === 'success' ? 'Selesai' : ($status === 'pending' ? 'Menunggu' : 'Dibatalkan'),
                'status_belajar' => $status === 'success' ? 'sedang_belajar' : 'menunggu_pembayaran',
                'created_at' => $trxDate,
                'updated_at' => $trxDate,
            ]);

            $methodChoice = $paymentMethods[$idx % count($paymentMethods)];

            Payment::create([
                'id' => (string) Str::uuid(),
                'booking_id' => $booking->id,
                'user_id' => $student->id,
                'tenant_id' => $tenant->id,
                'amount' => $course->price,
                'method' => $methodChoice['method'],
                'provider' => $methodChoice['provider'],
                'external_id' => 'MIDTRANS-' . strtoupper(Str::random(10)),
                'status' => $status,
                'paid_at' => $status === 'success' ? $trxDate : null,
                'notes' => 'Pembayaran otomatis via Midtrans Gateway Skilloka',
                'created_at' => $trxDate,
                'updated_at' => $trxDate,
            ]);
        }
    }
}
