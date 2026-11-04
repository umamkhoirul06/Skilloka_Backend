<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Models\Booking;
use App\Models\Course;
use App\Models\CourseContent;
use App\Models\Attendance;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

class MyCourseController extends BaseController
{
    /**
     * GET /api/my-courses
     * Menampilkan daftar kursus yang didaftarkan oleh pengguna login.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $bookings = Booking::with([
                'course.lpk',
                'course.category',
                'schedule',
                'payment',
            ])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $result = $bookings->map(function ($booking) {
            $course = $booking->course ?? optional($booking->schedule)->course;
            $lpk = $course ? ($course->lpk ?? optional($course->tenant)->lpk) : null;
            $schedule = $booking->schedule;

            // Cek status pembayaran
            $isPaid = (
                in_array(strtolower($booking->status), ['selesai', 'confirmed', 'paid', 'approved']) ||
                ($booking->payment && in_array(strtolower($booking->payment->status), ['paid', 'verified', 'success']))
            );

            // Klasifikasi tab: upcoming (menunggu), ongoing (berjalan), completed (selesai)
            $learningStatus = strtolower($booking->status_belajar ?? 'sedang_belajar');
            if ($isPaid) {
                if ($learningStatus === 'selesai_belajar' || strtolower($booking->status) === 'completed') {
                    $tabStatus = 'completed';
                    $statusLabel = 'Selesai';
                } else {
                    $tabStatus = 'ongoing';
                    $statusLabel = 'Sedang Berjalan';
                }
            } else {
                $tabStatus = 'upcoming';
                $statusLabel = 'Menunggu Pembayaran';
            }

            // Normalisasi gambar kursus
            $images = [];
            if ($course && !empty($course->images)) {
                if (is_array($course->images)) {
                    $images = $course->images;
                } elseif (is_string($course->images)) {
                    $decoded = json_decode($course->images, true);
                    $images = is_array($decoded) ? $decoded : [$course->images];
                }
            }
            $firstImage = !empty($images) ? $images[0] : null;

            // Format tanggal yang rapi dan ringkas (tidak menampilkan string ISO panjang)
            $formatCleanDate = function ($rawDate) {
                if (empty($rawDate)) return null;
                try {
                    $c = Carbon::parse($rawDate);
                    return $c->translatedFormat('d M Y');
                } catch (\Throwable $e) {
                    return substr((string) $rawDate, 0, 10);
                }
            };

            $startDateRaw = $schedule ? ($schedule->start_date ?? $schedule->date ?? null) : null;
            if (!$startDateRaw && $booking->booking_date) {
                $startDateRaw = $booking->booking_date;
            }
            $endDateRaw = $schedule ? ($schedule->end_date ?? null) : null;

            $startDate = $formatCleanDate($startDateRaw);
            $endDate = $formatCleanDate($endDateRaw);

            // Hitung progress nyata berdasarkan kehadiran
            $totalAttendances = Attendance::where('user_id', $booking->user_id)
                ->where('course_id', $booking->course_id)
                ->count();
            $presentAttendances = Attendance::where('user_id', $booking->user_id)
                ->where('course_id', $booking->course_id)
                ->whereIn('status', ['present', 'hadir'])
                ->count();

            $realProgress = $totalAttendances > 0 
                ? (int) round(($presentAttendances / $totalAttendances) * 100) 
                : ($tabStatus === 'completed' ? 100 : 0);

            return [
                'id' => $booking->id,
                'booking_id' => $booking->id,
                'code' => $booking->code ?? ('BKG-' . strtoupper(substr(str_replace('-', '', $booking->id), 0, 8))),
                'course_id' => $course ? $course->id : $booking->course_id,
                'title' => $course ? $course->title : 'Kursus Pelatihan',
                'course_title' => $course ? $course->title : 'Kursus Pelatihan',
                'lpk_name' => $lpk ? $lpk->name : ($course ? 'LPK Mitra Terdaftar' : 'Skilloka Partner'),
                'lpk' => [
                    'id' => $lpk ? $lpk->id : null,
                    'name' => $lpk ? $lpk->name : 'LPK Mitra Terdaftar',
                    'logo' => $lpk ? $lpk->logo : null,
                ],
                'images' => $images,
                'image' => $firstImage,
                'image_url' => $firstImage,
                'thumbnail' => $firstImage,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'schedule_start' => $startDate,
                'schedule_end' => $endDate,
                'status' => $tabStatus,
                'booking_status' => $booking->status,
                'status_belajar' => $learningStatus,
                'payment_status' => $booking->payment ? $booking->payment->status : ($isPaid ? 'success' : 'pending'),
                'total_price' => (float) $booking->total_price,
                'progress' => $realProgress,
                'category' => $course && $course->category ? $course->category->name : 'Pelatihan Kejuruan',
                'created_at' => $booking->created_at ? $booking->created_at->toIso8601String() : now()->toIso8601String(),
            ];
        });

        return $this->success($result, 'Daftar kursus saya berhasil dimuat.');
    }

    /**
     * GET /api/my-courses/{courseId}/content
     * Menampilkan daftar materi/modul pembelajaran kursus.
     */
    public function content(Request $request, string $courseId)
    {
        $user = $request->user();

        // Cari booking user untuk kursus ini
        $booking = Booking::with('payment')
            ->where('user_id', $user->id)
            ->where(function ($q) use ($courseId) {
                $q->where('course_id', $courseId)
                  ->orWhere('id', $courseId);
            })
            ->latest()
            ->first();

        $course = Course::find($courseId);
        if (!$course && $booking) {
            $course = Course::find($booking->course_id);
        }

        if (!$course) {
            return $this->error('Kursus tidak ditemukan.', 404);
        }

        // Cek apakah akses materi sudah terbuka (pembayaran lunas)
        $hasUnlocked = $booking && (
            in_array(strtolower($booking->status), ['selesai', 'confirmed', 'paid', 'approved']) ||
            ($booking->payment && in_array(strtolower($booking->payment->status), ['paid', 'verified', 'success']))
        );

        $contents = CourseContent::where('course_id', $course->id)
            ->where(function ($q) {
                $q->where('is_published', true)->orWhereNull('is_published');
            })
            ->orderBy('order_index', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        // 🌟 JIKA BELUM ADA MODUL FILE TAPI ADMIN LPK MENGISI SILABUS MATERI
        if ($contents->isEmpty() && !empty($course->syllabus)) {
            $rawLines = preg_split('/\r\n|\r|\n/', trim($course->syllabus));
            $lines = array_filter(array_map('trim', $rawLines));
            $parsedContents = collect();
            $idx = 1;
            foreach ($lines as $line) {
                if (empty($line)) continue;
                $cleanTitle = preg_replace('/^(\d+[\.\)]|[\-\*•–—])\s*/', '', $line);
                $lpkName = $course->lpk->name ?? 'LPK';
                $parsedContents->push((object)[
                    'id' => 'syllabus-' . $idx,
                    'title' => $cleanTitle ?: "Pokok Pembahasan $idx",
                    'type' => 'document',
                    'file_path' => null,
                    'content_url' => null,
                    'body' => "## " . ($cleanTitle ?: "Pokok Pembahasan $idx") . "\n\nPokok bahasan kejuruan resmi dari silabus pelatihan **{$course->title}** di **{$lpkName}**.\n\nPelajari materi ini sesuai panduan dan arahan instruktur LPK saat sesi kelas berlangsung.",
                    'order_index' => $idx,
                ]);
                $idx++;
            }
            if ($parsedContents->isNotEmpty()) {
                $contents = $parsedContents;
            }
        }

        $formatted = $contents->map(function ($item) use ($hasUnlocked) {
            $fileUrl = null;
            if ($item->file_path) {
                $fileUrl = asset('storage/' . $item->file_path);
            }
            $targetUrl = $item->content_url ?: $fileUrl;
            $hasPdf = ($item->type === 'document') || ($fileUrl && str_ends_with(strtolower($fileUrl), '.pdf'));

            $fallbackDesc = $hasPdf ? 'Dokumen file modul resmi dari instruktur LPK.' : 'Modul pembelajaran resmi Skilloka.';
            $fallbackContent = $hasPdf
                ? "## " . $item->title . "\n\nDokumen materi ini telah diunggah resmi oleh Admin LPK.\n\nSilakan klik tombol **Buka PDF / Unduh File** di atas untuk membaca dokumen modul secara lengkap."
                : "Silakan pelajari materi berikut sesuai panduan dan arahan instruktur LPK.";

            // 🔒 Keamanan: Jika kursus belum dibayar / belum di-ACC, sembunyikan link file dan isi materi
            if (!$hasUnlocked) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'type' => $item->type ?? 'document',
                    'is_locked' => true,
                    'description' => 'Materi terkunci. Selesaikan pembayaran kursus untuk mengakses modul ini.',
                    'content' => 'Materi ini terkunci. Silakan selesaikan pembayaran kursus untuk membaca modul dan mengunduh dokumen.',
                    'body' => null,
                    'url' => null,
                    'content_url' => null,
                    'file_url' => null,
                    'file_path' => null,
                    'order_index' => $item->order_index,
                ];
            }

            return [
                'id' => $item->id,
                'title' => $item->title,
                'type' => $item->type ?? 'document',
                'is_locked' => false,
                'description' => $item->body ? Str::limit(strip_tags($item->body), 160) : $fallbackDesc,
                'content' => $item->body ?: $fallbackContent,
                'body' => $item->body,
                'url' => $targetUrl,
                'content_url' => $targetUrl,
                'file_url' => $fileUrl,
                'file_path' => $item->file_path,
                'order_index' => $item->order_index,
            ];
        });

        return $this->success($formatted, 'Materi kursus berhasil dimuat.');
    }

    /**
     * GET /api/my-courses/{courseId}/attendance
     * Menampilkan riwayat presensi / kehadiran peserta (data nyata dari instruktur LPK).
     */
    public function attendance(Request $request, string $courseId)
    {
        $user = $request->user();

        $booking = Booking::with('schedule')
            ->where('user_id', $user->id)
            ->where(function ($q) use ($courseId) {
                $q->where('course_id', $courseId)
                  ->orWhere('id', $courseId);
            })
            ->latest()
            ->first();

        $realCourseId = $booking ? $booking->course_id : $courseId;

        // Ambil data absensi nyata yang telah diinput oleh instruktur/Admin LPK
        $attendances = Attendance::where('user_id', $user->id)
            ->where('course_id', $realCourseId)
            ->orderBy('session_date')
            ->get();

        // Jangan pernah membuat data dummy otomatis jika belum ada sesi yang dimulai
        $formatted = $attendances->map(function ($a) {
            return [
                'id' => $a->id,
                'session_name' => $a->session_name ?? 'Sesi Pelatihan',
                'session_date' => $a->session_date ? date('d M Y', strtotime($a->session_date)) : '-',
                'date' => $a->session_date ? date('d M Y', strtotime($a->session_date)) : '-',
                'status' => $a->status ?? 'pending',
                'is_present' => in_array(strtolower($a->status ?? ''), ['present', 'hadir']),
                'notes' => $a->notes,
            ];
        });

        return $this->success($formatted, 'Data presensi berhasil dimuat.');
    }

    /**
     * GET /api/my-courses/{courseId}/certificate
     * Menampilkan sertifikat kursus jika telah diterbitkan oleh Admin LPK setelah pelatihan selesai.
     */
    public function certificate(Request $request, string $courseId)
    {
        $user = $request->user();

        $booking = Booking::where('user_id', $user->id)
            ->where(function ($q) use ($courseId) {
                $q->where('course_id', $courseId)
                  ->orWhere('id', $courseId);
            })
            ->latest()
            ->first();

        $realCourseId = $booking ? $booking->course_id : $courseId;

        $certificate = Certificate::where('user_id', $user->id)
            ->where('course_id', $realCourseId)
            ->first();

        if (!$certificate) {
            return $this->success(null, 'Sertifikat belum tersedia. Sertifikat akan diterbitkan oleh LPK setelah menyelesaikan seluruh sesi pelatihan dan evaluasi kelulusan.');
        }

        return $this->success([
            'id' => $certificate->id,
            'number' => $certificate->number,
            'certificate_no' => $certificate->number,
            'issue_date' => $certificate->issue_date ? date('d M Y', strtotime($certificate->issue_date)) : date('d M Y'),
            'issued_at' => $certificate->issue_date ? date('d M Y', strtotime($certificate->issue_date)) : date('d M Y'),
            'file_url' => $certificate->file_url,
            'download_url' => $certificate->file_url,
        ], 'Sertifikat berhasil dimuat.');
    }
}
