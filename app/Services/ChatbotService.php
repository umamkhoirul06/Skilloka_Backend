<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Lpk;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotService
{
    /**
     * Generate response for user query
     */
    public function reply(string $userMessage, array $history = []): array
    {
        // 1. Sanitize input (PRD Section 20 Security Baseline)
        $cleanMessage = trim(strip_tags($userMessage));
        if (empty($cleanMessage)) {
            return [
                'reply' => 'Halo! Ada yang bisa Loka bantu mengenai program pelatihan, kursus, atau pendaftaran LPK di Skilloka? 😊',
                'source' => 'system'
            ];
        }

        // Limit length
        if (mb_strlen($cleanMessage) > 600) {
            $cleanMessage = mb_substr($cleanMessage, 0, 600);
        }

        // 2. Fetch live data for context
        $courseContext = $this->buildCourseContext();
        $lpkContext = $this->buildLpkContext();

        // 3. Check for Google Gemini API Key
        $apiKey = config('services.gemini.api_key') ?: env('GEMINI_API_KEY');
        $model = config('services.gemini.model') ?: env('GEMINI_MODEL', 'gemini-1.5-flash');

        if (!empty($apiKey)) {
            try {
                $geminiReply = $this->callGeminiApi($cleanMessage, $history, $courseContext, $lpkContext, $apiKey, $model);
                if (!empty($geminiReply)) {
                    return [
                        'reply' => $geminiReply,
                        'source' => 'gemini'
                    ];
                }
            } catch (\Throwable $e) {
                Log::warning('Gemini API call failed, falling back to smart contextual engine: ' . $e->getMessage());
            }
        }

        // 4. Fallback to Smart Contextual Engine (Always reliable & instant)
        $smartReply = $this->smartContextualReply($cleanMessage, $courseContext, $lpkContext);

        return [
            'reply' => $smartReply,
            'source' => 'smart_engine'
        ];
    }

    /**
     * Call Google Gemini REST API
     */
    protected function callGeminiApi(string $message, array $history, string $courseContext, string $lpkContext, string $apiKey, string $model): ?string
    {
        $systemInstruction = "Kamu adalah 'Loka', Asisten Virtual Cerdas resmi untuk platform Skilloka (https://skilloka.my.id).
Skilloka adalah platform digital terdepan di Kabupaten Indramayu yang mempertemukan calon peserta pelatihan kerja dengan Lembaga Pelatihan Kerja (LPK) terverifikasi resmi.

Karakter & Gaya Komunikasi:
- Ramah, antusias, solutif, santun, dan memotivasi generasi muda siap kerja.
- Gunakan Bahasa Indonesia yang luwes, jelas, dan profesional.
- Jawab secara ringkas, informatif, dan terstruktur (gunakan bullet points jika mendaftar kursus/langkah).
- Jangan membuat informasi palsu. Jika ditanya hal di luar pelatihan, LPK, karir, atau Skilloka, arahkan kembali dengan sopan ke topik Skilloka.

Informasi Resmi Platform Skilloka:
- Wilayah Operasional Utama: Kabupaten Indramayu & sekitarnya, Jawa Barat.
- Kantor Layanan: Kabupaten Indramayu, Jawa Barat. Email: info@skilloka.my.id, WhatsApp/CS: 0895-4051-93300.
- Sertifikasi: Standar BNSP (Badan Nasional Sertifikasi Profesi) & kurikulum sesuai standar industri.
- Metode Booking & Pembayaran: Booking online melalui platform, pembayaran aman via Midtrans (Virtual Account Bank, QRIS, GoPay, ShopeePay, dll).
- Untuk Mitra LPK: LPK dapat mendaftar melalui menu 'Gabung Sebagai Mitra LPK' di URL: /daftar-lpk. Diperlukan verifikasi izin legalitas (NIB/VIN LPK).
- Halaman Publik:
  * Katalog Kursus: /kursus
  * Direktori LPK: /lpk
  * Pendaftaran Mitra LPK: /daftar-lpk
  * Tentang Kami: /tentang

Data Real-Time Kursus Aktif di Skilloka Indramayu:
{$courseContext}

Data Real-Time LPK Mitra Terdaftar:
{$lpkContext}

Tugasmu adalah menjawab pertanyaan pengguna dengan akurat berdasarkan fakta di atas. Berikan saran kursus yang relevan jika ditanya kejuruan atau minat tertentu.";

        $contents = [];

        // Attach recent conversation history (max 4 turns)
        $trimmedHistory = array_slice($history, -4);
        foreach ($trimmedHistory as $item) {
            $role = ($item['sender'] ?? 'user') === 'user' ? 'user' : 'model';
            $text = trim($item['text'] ?? '');
            if (!empty($text)) {
                $contents[] = [
                    'role' => $role,
                    'parts' => [['text' => $text]]
                ];
            }
        }

        // Add current user prompt
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $message]]
        ];

        $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

        $payload = [
            'contents' => $contents,
            'systemInstruction' => [
                'parts' => [
                    ['text' => $systemInstruction]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.6,
                'topK' => 40,
                'topP' => 0.95,
                'maxOutputTokens' => 700,
            ]
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->timeout(12)->post($endpoint, $payload);

        if ($response->successful()) {
            $json = $response->json();
            $candidates = $json['candidates'] ?? [];
            if (!empty($candidates[0]['content']['parts'][0]['text'])) {
                return trim($candidates[0]['content']['parts'][0]['text']);
            }
        }

        Log::warning('Gemini API returned error: ' . $response->body());
        return null;
    }

    /**
     * Smart Rule-Based Engine tailored for Skilloka PRD v2.0
     */
    protected function smartContextualReply(string $msg, string $courseContext, string $lpkContext): string
    {
        $q = mb_strtolower($msg);

        // 1. Salam & Sapaan
        if (preg_match('/\b(halo|hai|hi|hello|selamat|assalamu|pagi|siang|sore|malam)\b/u', $q)) {
            return "Halo! 👋 Saya **Loka**, Asisten AI resmi Skilloka.\n\nSaya bisa membantu Anda mencari kursus keahlian bersertifikat, informasi Lembaga Pelatihan Kerja (LPK) di Indramayu, proses pendaftaran, hingga panduan bermitra bagi pengelola LPK.\n\nAda yang ingin Anda tanyakan hari ini?";
        }

        // 2. Apa itu Skilloka?
        if (str_contains($q, 'apa itu skilloka') || str_contains($q, 'tentang skilloka') || str_contains($q, 'platform apa')) {
            return "**Skilloka** adalah platform ekosistem digital pelatihan kerja resmi di Kabupaten Indramayu yang mempertemukan pencari keahlian dengan Lembaga Pelatihan Kerja (LPK) terverifikasi.\n\nMelalui Skilloka, Anda dapat:\n- 🎯 Menemukan kursus bersertifikat standar industri (BNSP).\n- 📅 Memilih jadwal pelatihan yang fleksibel.\n- 💳 Melakukan booking & pembayaran praktis via Midtrans.\n- 📈 Mengakses absensi digital, modul pelatihan, dan e-sertifikat.\n\nJelajahi kursus kami di menu [Katalog Kursus](/kursus)!";
        }

        // 3. Rekomendasi / Tanya Kursus Umum
        if (str_contains($q, 'kursus apa') || str_contains($q, 'daftar kursus') || str_contains($q, 'program pelatihan') || str_contains($q, 'rekomendasi kursus') || str_contains($q, 'ada kursus apa')) {
            return "Saat ini di Skilloka Indramayu tersedia beberapa program kejuruan unggulan siap kerja:\n\n"
                . "1. 💻 **Web Programming & Fullstack Modern** (Techno Skill Center) - Rp 1.500.000\n"
                . "2. 🎨 **Desain Grafis Komersial & Branding UMKM** (Techno Skill Center) - Rp 850.000\n"
                . "3. 🇯🇵 **Bahasa Jepang Vokasi & Tokutei Ginou** (Global English House) - Rp 2.500.000\n"
                . "4. 🚢 **Bahasa Inggris Kapal Pesiar & Hospitality** (Global English House) - Rp 2.000.000\n"
                . "5. 🔧 **Teknisi Otomotif & Servis Injeksi** (LPK Merah Putih) - Rp 1.250.000\n"
                . "6. ⚙️ **Operator Mesin Bubut & CNC Presisi** (LPK Merah Putih) - Rp 1.800.000\n\n"
                . "👉 Anda dapat melihat rincian silabus dan jadwal lengkap di [Lihat Seluruh Kursus](/kursus).";
        }

        // 3. Untuk LPK / Cara Bergabung Jadi Mitra
        if (str_contains($q, 'mitra') || (str_contains($q, 'lpk') && (str_contains($q, 'daftar') || str_contains($q, 'gabung') || str_contains($q, 'kerjasama') || str_contains($q, 'registrasi')))) {
            return "Untuk pengelola LPK di Kabupaten Indramayu, Anda dapat mendaftarkan lembaga Anda dengan mudah:\n\n"
                . "1. Kunjungi halaman [Daftar Sebagai Mitra LPK](/daftar-lpk).\n"
                . "2. Isi profil lembaga, kontak PIC, dan lampirkan legalitas (NIB / VIN / Izin LPK).\n"
                . "3. Tim Skilloka & Dinas terkait akan memverifikasi data LPK Anda.\n"
                . "4. Setelah disetujui, Anda mendapatkan akses ke **Dashboard Admin LPK** untuk mengelola program kursus, jadwal, siswa, dan absensi digital.\n\n"
                . "Pelajari manfaat lengkapnya di halaman [Untuk Mitra LPK](/untuk-lpk).";
        }

        // 4. Rekomendasi / Tanya Kursus Umum
        if (str_contains($q, 'kursus apa') || str_contains($q, 'daftar kursus') || str_contains($q, 'program pelatihan') || str_contains($q, 'rekomendasi kursus') || str_contains($q, 'ada kursus apa')) {
            return "Saat ini di Skilloka Indramayu tersedia beberapa program kejuruan unggulan siap kerja:\n\n"
                . "1. 💻 **Web Programming & Fullstack Modern** (Techno Skill Center) - Rp 1.500.000\n"
                . "2. 🎨 **Desain Grafis Komersial & Branding UMKM** (Techno Skill Center) - Rp 850.000\n"
                . "3. 🇯🇵 **Bahasa Jepang Vokasi & Tokutei Ginou** (Global English House) - Rp 2.500.000\n"
                . "4. 🚢 **Bahasa Inggris Kapal Pesiar & Hospitality** (Global English House) - Rp 2.000.000\n"
                . "5. 🔧 **Teknisi Otomotif & Servis Injeksi** (LPK Merah Putih) - Rp 1.250.000\n"
                . "6. ⚙️ **Operator Mesin Bubut & CNC Presisi** (LPK Merah Putih) - Rp 1.800.000\n\n"
                . "👉 Anda dapat melihat rincian silabus dan jadwal lengkap di [Lihat Seluruh Kursus](/kursus).";
        }

        // 5. Tanya Kursus IT / Komputer / Desain / Web
        if (preg_match('/\b(it|komputer|coding|program|programming|web|desain|grafis)\b/u', $q)) {
            return "Untuk bidang **Teknologi & Digital**, kami menyediakan kursus unggulan dari **Techno Skill Center Indramayu**:\n\n"
                . "• **Web Programming & Fullstack Modern**: Mempelajari pengembangan aplikasi web siap industri (Laravel/Node/React) - Rp 1.500.000.\n"
                . "• **Desain Grafis Komersial & Branding UMKM**: Menguasai Adobe Photoshop, Illustrator, & canva branding bisnis - Rp 850.000.\n\n"
                . "Kedua program ini didampingi instruktur praktisi dan portofolio nyata. Cek selengkapnya di [Katalog Kursus](/kursus).";
        }

        // 6. Tanya Otomotif / Mesin / CNC / Welder
        if (preg_match('/\b(otomotif|motor|mobil|mesin|bubut|cnc|welder|las)\b/u', $q)) {
            return "Untuk bidang **Teknik Otomotif & Manufaktur**, Anda dapat mendaftar di **LPK Merah Putih Indramayu**:\n\n"
                . "• **Teknisi Otomotif & Servis Injeksi**: Praktik diagnosis mesin injeksi, tune-up, dan kelistrikan modern - Rp 1.250.000.\n"
                . "• **Operator Mesin Bubut & CNC Presisi**: Pengoperasian mesin bubut konvensional & CNC standar pabrikasi - Rp 1.800.000.\n\n"
                . "Fasilitas bengkel lengkap dan sertifikasi standar industri. Informasi pendaftaran di [Katalog Kursus](/kursus).";
        }

        // 7. Tanya Bahasa Asing (Jepang / Inggris / Luar Negeri / Kapal Pesiar)
        if (preg_match('/\b(bahasa|jepang|inggris|tokutei|magang|kapal pesiar|hospitality)\b/u', $q)) {
            return "Untuk persiapan kerja di luar negeri & industri perhotelan, kami memiliki program intensif dari **Global English House Indramayu**:\n\n"
                . "• **Bahasa Jepang Vokasi & Tokutei Ginou**: Persiapan kerja formal ke Jepang (JLPT N4/JFT Basic) - Rp 2.500.000.\n"
                . "• **Bahasa Inggris Kapal Pesiar & Hospitality**: Pelatihan percakapan, interview kapal pesiar & hotel internasional - Rp 2.000.000.\n\n"
                . "Lihat jadwal pembukaan kelas terbaru di [Halaman Kursus](/kursus)!";
        }

        // 8. Cara Daftar Kursus / Booking
        if (str_contains($q, 'cara daftar') || str_contains($q, 'cara booking') || str_contains($q, 'cara ikut') || str_contains($q, 'cara pesan') || str_contains($q, 'bagaimana daftar')) {
            return "Berikut langkah mudah mendaftar kursus di Skilloka:\n\n"
                . "1. Buka menu [Cari Kursus](/kursus) di Skilloka.\n"
                . "2. Pilih program pelatihan yang Anda minati untuk melihat silabus & jadwal.\n"
                . "3. Klik tombol **Pilih Jadwal & Daftar**.\n"
                . "4. Lakukan pembayaran aman menggunakan metode Midtrans (Transfer Bank, QRIS, e-Wallet).\n"
                . "5. Setelah pembayaran terverifikasi, Anda resmi terdaftar dan bisa mengakses materi serta jadwal pelatihan!";
        }

        // 9. Cara Pembayaran & Biaya
        if (preg_match('/\b(bayar|pembayaran|biaya|harga|transfer|qris|midtrans|rekening)\b/u', $q)) {
            return "Metode pembayaran di Skilloka sangat aman dan terintegrasi melalui payment gateway **Midtrans**:\n\n"
                . "• **QRIS**: BCA, Gopay, OVO, Dana, LinkAja, ShopeePay.\n"
                . "• **Virtual Account (VA)**: BCA, Mandiri, BNI, BRI, Permata.\n"
                . "• **Indomaret / Alfamart** (Gerai Ritel).\n\n"
                . "Semua pembayaran otomatis diverifikasi dalam hitungan detik tanpa perlu upload struk manual.";
        }

        // 10. Sertifikat BNSP / Kelulusan
        if (preg_match('/\b(sertifikat|bnsp|ijazah|kelulusan|lisensi)\b/u', $q)) {
            return "Pelatihan di Skilloka mengacu pada standar **BNSP (Badan Nasional Sertifikasi Profesi)** dan SKKNI (Standar Kompetensi Kerja Nasional Indonesia).\n\nSetelah menyelesaikan jam pelatihan dan dinyatakan kompeten, peserta akan memperoleh:\n- 📜 **Sertifikat Pelatihan Resmi dari LPK** terakreditasi.\n- 🎖️ Fasilitas uji kompetensi sertifikasi profesi BNSP (sesuai skema tiap LPK).\n- 💼 Rekomendasi ke jaringan mitra industri penerima tenaga kerja.";
        }

        // 11. Lokasi / Kantor / Kontak CS
        if (str_contains($q, 'lokasi') || str_contains($q, 'alamat') || str_contains($q, 'kantor') || str_contains($q, 'kontak') || str_contains($q, 'wa') || str_contains($q, 'telepon') || str_contains($q, 'hubungi')) {
            return "📍 **Wilayah Layanan Skilloka**:\n"
                . "Kabupaten Indramayu dan sekitarnya, Jawa Barat, Indonesia.\n\n"
                . "📞 **Kontak Bantuan & Customer Service**:\n"
                . "• WhatsApp: **0895-4051-93300**\n"
                . "• Email: **info@skilloka.my.id**\n"
                . "• Jam Operasional: Senin - Sabtu (08.00 - 17.00 WIB)\n\n"
                . "Tim kami siap melayani pertanyaan seputar pendaftaran peserta maupun kemitraan lembaga.";
        }

        // 12. Daftar LPK yang Terdaftar
        if (str_contains($q, 'daftar lpk') || str_contains($q, 'lembaga mana saja') || str_contains($q, 'lpk yang ada')) {
            return "Beberapa LPK terverifikasi di Skilloka saat ini antara lain:\n\n"
                . "• **Techno Skill Center Indramayu** (Jl. Mayor Dasuki No. 88, Jatibarang)\n"
                . "• **Global English House Indramayu** (Jl. Jend. Ahmad Yani No. 12, Haurgeulis)\n"
                . "• **LPK Merah Putih Indramayu** (Jl. Jend. Sudirman No. 45, Karanganyar)\n"
                . "• **LPK Genki Mirai** (Kecamatan Lohbener, Indramayu)\n\n"
                . "Lihat profil lengkap dan portofolio LPK di [Direktori Lembaga LPK](/lpk).";
        }

        // 13. Default Helpful Answer
        return "Pertanyaan yang bagus! Skilloka adalah portal pencarian dan booking kursus pelatihan kerja bersertifikat (standar BNSP) di Indramayu.\n\n"
            . "Anda bisa menanyakan seputar:\n"
            . "• 🔍 Rekomendasi kursus (IT, Otomotif, Bahasa Asing, Mesin CNC, Desain)\n"
            . "• 💳 Cara daftar dan pembayaran kursus\n"
            . "• 📜 Sertifikasi kompetensi BNSP\n"
            . "• 🏢 Cara mendaftar menjadi mitra LPK di Indramayu\n\n"
            . "Ada yang spesifik ingin Anda ketahui lebih detail? 😊";
    }

    /**
     * Build formatted string of active courses for AI prompt context
     */
    protected function buildCourseContext(): string
    {
        try {
            $courses = Course::with('lpk', 'category')->take(10)->get();
            if ($courses->isEmpty()) {
                return "Belum ada kursus aktif.";
            }

            $list = [];
            foreach ($courses as $c) {
                $cat = $c->category->name ?? 'Kejuruan';
                $lpk = $c->lpk->name ?? 'Mitra LPK';
                $price = number_format($c->price ?? 0, 0, ',', '.');
                $list[] = "- {$c->title} (Kategori: {$cat}, LPK: {$lpk}, Biaya: Rp {$price})";
            }
            return implode("\n", $list);
        } catch (\Throwable $e) {
            return "- Web Programming (Techno Skill Center) - Rp 1.500.000\n- Desain Grafis (Techno Skill Center) - Rp 850.000\n- Bahasa Jepang Tokutei Ginou (Global English House) - Rp 2.500.000\n- Teknisi Otomotif Injeksi (LPK Merah Putih) - Rp 1.250.000";
        }
    }

    /**
     * Build formatted string of active LPKs for AI prompt context
     */
    protected function buildLpkContext(): string
    {
        try {
            $lpks = Lpk::where('status', 'active')->take(10)->get();
            if ($lpks->isEmpty()) {
                return "Belum ada LPK terdaftar.";
            }

            $list = [];
            foreach ($lpks as $l) {
                $list[] = "- {$l->name} (Alamat: {$l->address})";
            }
            return implode("\n", $list);
        } catch (\Throwable $e) {
            return "- Techno Skill Center Indramayu (Jatibarang)\n- Global English House Indramayu (Haurgeulis)\n- LPK Merah Putih Indramayu (Karanganyar)\n- LPK Genki Mirai (Lohbener)";
        }
    }
}
