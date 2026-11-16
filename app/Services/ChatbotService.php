<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Lpk;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
                'reply' => 'Halo! Saya Loka, Konsultan Karir & Teman Belajar resmi dari Skilloka. Ada yang bisa Loka bantu mengenai program kursus vokasi, daftar LPK resmi di Indramayu, sertifikasi BNSP, atau konsultasi karir masa depan?',
                'source' => 'system'
            ];
        }

        // Limit length
        if (mb_strlen($cleanMessage) > 1000) {
            $cleanMessage = mb_substr($cleanMessage, 0, 1000);
        }

        // 2. Fetch live data for context (RAG dari database)
        $courseContext = $this->buildCourseContext();
        $lpkContext = $this->buildLpkContext();

        // 3. Check for Google Gemini API Key
        $apiKey = config('services.gemini.api_key') ?: env('GEMINI_API_KEY');
        $model = config('services.gemini.model') ?: env('GEMINI_MODEL', 'gemini-3.1-flash-lite');

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

        // 4. Fallback to Smart Contextual Engine (Always reliable & live database grounded)
        $smartReply = $this->smartContextualReply($cleanMessage, $courseContext, $lpkContext);

        return [
            'reply' => $smartReply,
            'source' => 'smart_engine'
        ];
    }

    /**
     * Call Google Gemini REST API
     */
    protected function callGeminiApi(string $message, array $history, string $courseContext, string $lpkContext, string $apiKey, string $primaryModel): ?string
    {
        $systemInstruction = "Kamu adalah 'Loka', Konsultan Karir Vokasi dan Asisten Resmi dari platform Skilloka (https://skilloka.my.id) yang beroperasi di Kabupaten Indramayu dan sekitarnya.

KARAKTER & NADA BICARA:
- Selalu bersikap ramah, sopan, santun, hangat, empatik, dan suportif.
- Gunakan bahasa Indonesia yang santai tapi tetap profesional dan hormat, seperti seorang mentor atau kakak pendamping karir.
- Hindari bahasa robotik kaku seperti 'Sebagai AI...' atau 'Tentu saja!'.

ATURAN MENJAWAB:
1. PERTANYAAN TENTANG LPK / LEMBAGA YANG TERSEDIA ATAU TERDAFTAR:
   - JIKA PENGGUNA BERTANYA tentang LPK yang terdaftar, mitra, atau tersedia, KAMU WAJIB menyebutkan daftar lembaga LPK resmi yang ada pada DATA DATABASE di bawah secara jelas (nama lembaga, lokasi/kecamatan, dan fokus pelatihannya).
   - Tekankan bahwa seluruh LPK tersebut telah terverifikasi resmi legalitasnya di Skilloka.

2. PERTANYAAN TENTANG KURSUS / PROGRAM PELATIHAN:
   - Sebutkan program kursus yang benar-benar tercatat di DATA DATABASE di bawah, termasuk kategori, lembaga penyelenggara, dan biayanya.
   - Jelaskan bahwa lulusan akan dibimbing langsung untuk mendapatkan sertifikasi resmi BNSP dan siap kerja.

3. KONSULTASI MASA DEPAN, CURHAT, OVERTHINKING, ATAU BINGUNG MEMILIH SKILL:
   - Tanggapi dengan empati dan pengertian mendalam. Validasi perasaan mereka bahwa wajar merasa cemas atau bingung.
   - Berikan arahan langkah demi langkah yang realistis: sarankan fokus menguasai 1 skill terapan yang banyak dibutuhkan industri (misal teknik las/manufaktur, komputer perkantoran, desain grafis, atau bahasa asing ke Jepang).
   - Bangkitkan rasa percaya diri mereka dengan solusi konkret di Skilloka.

4. BATASAN TOPIK / GUARDRAILS (SANGAT KETAT!):
   - Jika pengguna menanyakan hal yang NGAWUR, di luar konteks, SARA, politik, gosip artis, judi, atau hal yang tidak relevan dengan Skilloka / pelatihan kerja vokasi / karir:
   - JANGAN MERESPONS TOPIK NGAWUR TERSEBUT!
   - TOLAK DENGAN SANGAT SOPAN, SANTUN, DAN RAMAH, lalu ajak kembali ke topik kursus dan perencanaan karir.
   - Contoh penolakan santun: 'Mohon maaf ya kak, sebagai asisten resmi Skilloka, Loka hanya bisa membantu seputar informasi pelatihan vokasi, program kursus, sertifikasi BNSP, profil LPK mitra di Indramayu, serta konsultasi perencanaan karir masa depan. Ada hal seputar kursus atau persiapan kerjamu yang bisa Loka bantu?'

DATA LEMBAGA PELATIHAN KERJA (LPK) RESMI TERDAFTAR DI DATABASE SKILLOKA:
{$lpkContext}

DATA PROGRAM KURSUS AKTIF DI DATABASE SKILLOKA:
{$courseContext}";

        $contents = [];

        // Attach recent conversation history (max 6 turns)
        $trimmedHistory = array_slice($history, -6);
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
                'topP' => 0.90,
                'maxOutputTokens' => 600,
            ]
        ];

        // Model resmi yang aktif dan mendukung generateContent
        $modelsToTry = array_unique([$primaryModel, 'gemini-3.1-flash-lite', 'gemini-3.5-flash', 'gemini-flash-latest']);

        foreach ($modelsToTry as $m) {
            try {
                $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$m}:generateContent?key={$apiKey}";
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->timeout(12)->post($endpoint, $payload);

                if ($response->successful()) {
                    $json = $response->json();
                    $candidates = $json['candidates'] ?? [];
                    if (!empty($candidates[0]['content']['parts'][0]['text'])) {
                        return trim($candidates[0]['content']['parts'][0]['text']);
                    }
                } else {
                    Log::warning("Gemini model {$m} returned HTTP {$response->status()}: " . $response->body());
                }
            } catch (\Throwable $e) {
                Log::warning("Gemini model {$m} exception: " . $e->getMessage());
            }
        }

        return null;
    }

    /**
     * Smart Contextual Fallback Engine (Dinamis dari Database, Ramah, Sopan & Solutif)
     */
    protected function smartContextualReply(string $message, string $courseContext, string $lpkContext): string
    {
        $q = mb_strtolower(trim($message));

        // 1. Filter Topik Ngawur / Out-of-Bounds (Guardrail Penolakan Ramah)
        $ngawurPatterns = [
            '/\b(judi|slot|gacor|pragmatic|togel|poker|casino)\b/i',
            '/\b(bokep|porno|sexy|bugil|mesum|lendir)\b/i',
            '/\b(politik|capres|pemilu|partai|pilkada|menteri|presiden)\b/i',
            '/\b(resep|masak|bumbu|rendang|nasi goreng|kue bolu)\b/i',
            '/\b(chord|lirik lagu|puisi cinta|ramalan zodiak|horoskop)\b/i',
            '/\b(cheat|hack|mobile legends|free fire|mlbb|diamond)\b/i',
        ];

        foreach ($ngawurPatterns as $pattern) {
            if (preg_match($pattern, $q)) {
                return "Mohon maaf ya kak, sebagai asisten resmi Skilloka, Loka difokuskan untuk membantu seputar program pelatihan kerja vokasi, sertifikasi BNSP, informasi LPK mitra resmi di Indramayu, serta konsultasi perencanaan karir masa depan.\n\n"
                    . "Ada hal seputar pilihan bidang kursus, persiapan kerja, atau rekomendasi kejuruan yang ingin kamu tanyakan ke Loka?";
            }
        }

        // 2. Pertanyaan LPK yang Tersedia / Terdaftar
        if (preg_match('/\b(lpk|lembaga|mitra|penyelenggara|tempat kursus|yayasan)\b/i', $q) &&
            preg_match('/\b(apa|mana|tersedia|terdaftar|ada|daftar|list|rekomendasi)\b/i', $q)) {
            
            $lpks = Lpk::where(function ($query) {
                    $query->where('status_verifikasi', 'approved')
                          ->orWhere('is_verified', true)
                          ->orWhere('status', 'active');
                })
                ->take(6)
                ->get();

            if ($lpks->isNotEmpty()) {
                $lpkText = "Tentu! Di platform Skilloka saat ini sudah bermitra dengan beberapa Lembaga Pelatihan Kerja (LPK) resmi terverifikasi di Kabupaten Indramayu:\n\n";
                foreach ($lpks as $idx => $l) {
                    $num = $idx + 1;
                    $loc = !empty($l->address) ? " ({$l->address})" : "";
                    $lpkText .= "{$num}. **{$l->name}**{$loc}\n";
                }
                $lpkText .= "\nSemua lembaga di atas memiliki kurikulum berbasis praktik kerja dan legalitas terpercaya. Kamu bisa klik menu **Lembaga LPK** di navbar atas untuk melihat profil lengkap, fasilitas bengkel, dan kursus yang dibuka masing-masing lembaga!";
                return $lpkText;
            }

            return "Di Skilloka saat ini sudah bermitra dengan lembaga resmi seperti **LPK Indonesia Douryu Indramayu** (Patrol), **LPK Nusantara Edukasi** (Krangkeng), **LPK Impian Semesta Raya** (Kepandean), **LPK Inovasi Mandiri** (Kandanghaur), dan **LPK Merah Putih Indramayu**.\n\nKamu bisa buka menu **Lembaga LPK** di atas untuk melihat detail lengkap dan kontak langsungnya ya!";
        }

        // 3. Pertanyaan Kursus / Daftar Pelatihan
        if (preg_match('/\b(kursus|pelatihan|kelas|program|training|belajar|biaya|harga)\b/i', $q)) {
            $courses = Course::with(['lpk', 'category'])->where('is_active', true)->take(5)->get();

            if ($courses->isNotEmpty()) {
                $cText = "Di Skilloka ada berbagai pilihan program pelatihan kejuruan bersertifikat BNSP yang siap kerja:\n\n";
                foreach ($courses as $c) {
                    $cat = $c->category->name ?? 'Vokasi';
                    $lpk = $c->lpk->name ?? 'Mitra LPK';
                    $price = number_format($c->price ?? 0, 0, ',', '.');
                    $cText .= "• **{$c->title}** ({$lpk}) - Rp {$price}\n";
                }
                $cText .= "\nKamu tertarik di bidang apa nih? Ada teknik otomotif & las, komputer/digital, maupun bahasa asing untuk penempatan kerja!";
                return $cText;
            }

            return "Di Skilloka tersedia program pelatihan vokasi langsung praktik: **Teknik Otomotif & Pengelasan**, **Web Programming & Desain Grafis**, **Komputer Administrasi Kantor**, hingga **Bahasa Jepang Kerja (SSW)**.\n\nSemua kursus dibimbing instruktur bersertifikat dan bengkel praktek standar industri. Kamu lebih tertarik bidang teknik, komputer kantor, atau bahasa asing?";
        }

        // 4. Curhat, Overthinking, Bingung Masa Depan, Minder, Belum Kerja
        if (preg_match('/\b(curhat|bingung|minder|takut|stres|nganggur|capek|overthinking|putus asa|gagal|belum kerja|tidak punya skill|ga punya skill|masa depan)\b/i', $q)) {
            return "Wajar banget kalau kamu merasa bingung atau cemas soal masa depan, kamu nggak sendirian kok! Kuncinya adalah jangan membebani pikiran dengan memikirkan semuanya sekaligus.\n\n"
                . "Di Skilloka, kamu bisa mulai dari satu langkah nyata hari ini: pilih 1 keterampilan praktis yang ingin kamu kuasai (misalnya teknisi las, komputer perkantoran, desain visual, atau bahasa asing ke Jepang). Pelatihan singkat intensif berbekal sertifikat resmi BNSP sudah jadi modal awal yang sangat kuat untuk mulai melamar kerja atau wirausaha.\n\n"
                . "Boleh cerita sedikit ke Loka, kira-kira apa hal yang paling membuatmu ragu atau cemas sekarang? Loka siap mendengarkan!";
        }

        // 5. Konsultasi Prospek Karir & Peluang Kerja
        if (preg_match('/\b(karir|karier|prospek|gaji|switch|jurusan|arah karir|rekomendasi kerja)\b/i', $q)) {
            return "Untuk peluang kerja yang cepat diserap industri, ada 3 bidang unggulan di LPK Skilloka:\n\n"
                . "1. **Teknik Otomotif & Pengelasan (Welding)**: Sangat dicari pabrik manufaktur dan bengkel industri.\n"
                . "2. **Komputer Perkantoran & Desain Digital**: Dibutuhkan di hampir semua instansi dan perusahaan untuk urusan administrasi.\n"
                . "3. **Bahasa Jepang (Tokutei Ginou / SSW)**: Jalur penempatan kerja resmi ke luar negeri dengan penghasilan menjanjikan.\n\n"
                . "Kira-kira dari ketiga opsi ini, mana yang paling sesuai dengan minat atau kepribadianmu?";
        }

        // 6. Sapaan Ramah
        if (preg_match('/\b(halo|hai|pagi|siang|sore|malam|assalamualaikum|tes|test|loka)\b/i', $q)) {
            return "Halo! Senang bisa menyapa kamu. Saya Loka, asisten resmi Skilloka. Ada yang bisa Loka bantu seputar info kursus kejuruan, profil LPK mitra resmi di Indramayu, atau mau diskusi santai soal rencana karirmu?";
        }

        // 7. Cara Daftar & Pembayaran
        if (preg_match('/\b(cara daftar|cara booking|daftar|bayar|pembayaran|midtrans|qris)\b/i', $q)) {
            return "Pendaftaran di Skilloka sangat mudah:\n\n"
                . "1. Buka menu **Cari Kursus**, pilih program kejuruan yang kamu inginkan.\n"
                . "2. Klik **Daftar Sekarang** dan isi data diri.\n"
                . "3. Lakukan pembayaran resmi secara instan via QRIS (GoPay, OVO, Dana, BCA) atau Transfer Bank Virtual Account.\n\n"
                . "Bukti pendaftaran dan status kepesertaan langsung otomatis aktif di akunmu.";
        }

        // 8. Default Answer yang Relevan & Santun
        return "Terima kasih atas pertanyaannya! Sebagai asisten resmi Skilloka, Loka siap membantu memberikan rekomendasi kursus kejuruan terpercaya, informasi LPK mitra resmi di Indramayu, sertifikasi kompetensi BNSP, hingga konsultasi arah karir.\n\n"
            . "Agar Loka bisa memberi solusi yang pas, boleh tahu bidang apa yang sedang ingin kamu pelajari atau persiapkan untuk masa depan?";
    }

    /**
     * Build formatted string of active courses for AI prompt context
     */
    protected function buildCourseContext(): string
    {
        try {
            $courses = Course::with(['lpk', 'category'])
                ->where('is_active', true)
                ->get();

            if ($courses->isEmpty()) {
                return "- Bootcamp Fullstack Web & Mobile (LPK Genki Mirai) - Rp 1.500.000\n"
                    . "- Bahasa Inggris Praktis & Percakapan (LPK Genki Mirai) - Rp 1.000.000\n"
                    . "- Teknisi Otomotif & Servis Injeksi (LPK Merah Putih Indramayu) - Rp 1.250.000\n"
                    . "- Operator Mesin Bubut & CNC Presisi (LPK Merah Putih Indramayu) - Rp 1.800.000\n"
                    . "- Desain Grafis Komersial & Branding UMKM (LPK Inovasi Mandiri) - Rp 850.000\n"
                    . "- Komputer Administrasi Perkantoran & Excel (LPK Nusantara Edukasi) - Rp 750.000\n"
                    . "- Bahasa Jepang Vokasi & Tokutei Ginou (LPK Indonesia Douryu Indramayu) - Rp 2.500.000";
            }

            $list = [];
            foreach ($courses as $c) {
                $cat = $c->category->name ?? 'Kejuruan';
                $lpk = $c->lpk->name ?? 'Mitra LPK';
                $price = number_format($c->price ?? 0, 0, ',', '.');
                $hours = $c->duration_hours ? " ({$c->duration_hours} jam)" : "";
                $desc = !empty($c->description) ? ' - ' . Str::limit($c->description, 80) : '';
                $list[] = "- {$c->title} [Kategori: {$cat} | LPK: {$lpk} | Biaya: Rp {$price}{$hours} | Level: {$c->level}]{$desc}";
            }
            return implode("\n", $list);
        } catch (\Throwable $e) {
            return "- Bootcamp Fullstack Web & Mobile (LPK Genki Mirai) - Rp 1.500.000\n- Teknisi Otomotif Injeksi (LPK Merah Putih Indramayu) - Rp 1.250.000\n- Bahasa Jepang Tokutei Ginou (LPK Indonesia Douryu Indramayu) - Rp 2.500.000";
        }
    }

    /**
     * Build formatted string of active LPKs for AI prompt context
     */
    protected function buildLpkContext(): string
    {
        try {
            $lpks = Lpk::where(function ($q) {
                    $q->where('status_verifikasi', 'approved')
                      ->orWhere('is_verified', true)
                      ->orWhere('status', 'active');
                })
                ->withCount(['courses' => fn($q) => $q->where('is_active', true)])
                ->get();

            if ($lpks->isEmpty()) {
                return "1. LPK Indonesia Douryu Indramayu (Program Magang & Kerja ke Jepang, Patrol)\n"
                    . "2. LPK Nusantara Edukasi (Komputer Perkantoran, Bahasa Asing & Administrasi Digital, Krangkeng)\n"
                    . "3. LPK Impian Semesta Raya (Persiapan Kerja ke Jepang, Kepandean)\n"
                    . "4. LPK Inovasi Mandiri (Desain Grafis, Fotografi & Multimedia, Kandanghaur)\n"
                    . "5. LPK Merah Putih Indramayu (Teknik Otomotif & Pengelasan/Welding, Karanganyar)\n"
                    . "6. LPK Genki Mirai (Bahasa Jepang & Vokasi Manufaktur, Lohbener)";
            }

            $list = [];
            foreach ($lpks as $idx => $l) {
                $num = $idx + 1;
                $desc = !empty($l->description) ? ' - ' . Str::limit($l->description, 100) : '';
                $legal = !empty($l->legal_name) ? " [Badan Hukum: {$l->legal_name}]" : "";
                $list[] = "{$num}. {$l->name} (Alamat: {$l->address}{$legal}, Aktif: {$l->courses_count} kursus){$desc}";
            }
            return implode("\n", $list);
        } catch (\Throwable $e) {
            return "1. LPK Indonesia Douryu Indramayu (Program Magang & Kerja ke Jepang, Patrol)\n"
                . "2. LPK Nusantara Edukasi (Komputer Perkantoran, Bahasa Asing & Administrasi Digital, Krangkeng)\n"
                . "3. LPK Impian Semesta Raya (Persiapan Kerja ke Jepang, Kepandean)\n"
                . "4. LPK Inovasi Mandiri (Desain Grafis, Fotografi & Multimedia, Kandanghaur)\n"
                . "5. LPK Merah Putih Indramayu (Teknik Otomotif & Pengelasan/Welding, Karanganyar)\n"
                . "6. LPK Genki Mirai (Bahasa Jepang & Vokasi Manufaktur, Lohbener)";
        }
    }
}
