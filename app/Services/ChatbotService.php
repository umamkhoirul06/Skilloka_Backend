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
                'reply' => 'Halo! Saya Loka, Konsultan Karir & Teman Curhat resmi Skilloka. Ada yang bisa Loka bantu mengenai program pelatihan, kursus, perencanaan karir, atau mau curhat seputar pekerjaan?',
                'source' => 'system'
            ];
        }

        // Limit length
        if (mb_strlen($cleanMessage) > 1000) {
            $cleanMessage = mb_substr($cleanMessage, 0, 1000);
        }

        // 2. Fetch live data for context
        $courseContext = $this->buildCourseContext();
        $lpkContext = $this->buildLpkContext();

        // 3. Check for Google Gemini API Key
        $apiKey = config('services.gemini.api_key') ?: env('GEMINI_API_KEY');
        $model = config('services.gemini.model') ?: env('GEMINI_MODEL', 'gemini-3.5-flash-lite');

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
    protected function callGeminiApi(string $message, array $history, string $courseContext, string $lpkContext, string $apiKey, string $primaryModel): ?string
    {
        $systemInstruction = "Kamu adalah 'Loka', teman ngobrol & konsultan karir vokasi dari Skilloka (https://skilloka.my.id).

ATURAN GAYA JAWABAN (WAJIB DIPATUHI - NATURAL, RINGKAS & HEMAT TOKEN):
1. Bahasa Alami & Manusiawi:
   - Bicara santai, ramah, dan bersahabat seperti kakak mentor atau teman ngobrol.
   - DILARANG menggunakan kata klise AI robotik (misal: 'Tentu saja!', 'Pertama-tama peluk jauh untukmu', 'Tarik napas dalam-dalam', 'Sebagai asisten AI...', dll). Jangan berlebihan atau terdengar dibuat-buat.
2. Singkat, Padat & To The Point (Maksimal 2-3 Paragraf Singkat):
   - JANGAN bertele-tele atau membuat karangan esai panjang.
   - Langsung masuk ke inti jawaban atau solusi praktis.
   - Tulis secara ringkas dan enak dibaca di layar HP/widget chat tanpa bikin lelah scrolling.
3. Fokus Solusi:
   - Jika curhat/bingung: Dengarkan singkat, tenangkan secara wajar tanpa drama, lalu beri 1-2 opsi langkah nyata hari ini.
   - Jika tanya kursus/karir: Sebutkan opsi bidang LPK Skilloka yang cocok (Teknik/Las/Otomotif, Komputer/Desain, Bahasa Asing SSW ke Jepang, atau Bisnis/Hospitality) serta keunggulan sertifikasi resmi BNSP.
4. Akhiri dengan 1 Pertanyaan Singkat:
   - Selipkan 1 pertanyaan santai yang relevan di akhir agar obrolan mengalir natural.

Data Kursus Aktif di Skilloka:
{$courseContext}

Data LPK Mitra Resmi:
{$lpkContext}";

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
                'temperature' => 0.7,
                'topK' => 40,
                'topP' => 0.90,
                'maxOutputTokens' => 350,
            ]
        ];

        // Models to try in priority order
        $modelsToTry = array_unique([$primaryModel, 'gemini-3.5-flash-lite', 'gemini-3.5-flash', 'gemini-3.8-flash']);

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
     * Smart Contextual Fallback Engine (Ringkas, Alami & Solutif)
     */
    protected function smartContextualReply(string $message, string $courseContext, string $lpkContext): string
    {
        $q = mb_strtolower($message);

        // 1. Curhat, Overthinking, Minder, Bingung Masa Depan
        if (preg_match('/(curhat|bingung|minder|takut|stres|nganggur|capek|overthinking|putus asa|gagal|belum kerja|tidak punya skill|ga punya skill)/u', $q)) {
            return "Wajar banget ngerasa bingung atau cemas, kamu nggak sendirian kok. Kuncinya jangan mikir terlalu jauh dulu ya.

"
                . "Coba mulai dari langkah kecil: fokus asah 1 skill praktis di LPK Skilloka (misal komputer, las, atau desain grafis). Pelatihan singkat plus sertifikat resmi BNSP sudah cukup banget buat modal awal melamar kerja.

"
                . "Kira-kira apa hal yang bikin kamu paling ragu sekarang? Cerita aja santai, Loka siap dengerin.";
        }

        // 2. Konsultasi Karir & Switch Career
        if (preg_match('/(karir|karier|prospek|gaji|switch|jurusan|arah karir|rekomendasi kerja)/u', $q)) {
            return "Untuk peluang kerja cepat, ada 3 bidang unggulan di LPK Skilloka:

"
                . "1. **Teknik & Manufaktur**: Pengelasan (welding) dan otomotif selalu dicari kawasan industri.
"
                . "2. **Komputer & Digital**: Admin kantor, Excel, atau desain grafis fleksibel untuk berbagai perusahaan.
"
                . "3. **Bahasa Asing (Jepang/Korea)**: Program magang SSW ke luar negeri dengan gaji menarik.

"
                . "Kamu sendiri lebih tertarik ke bidang teknik, kantor, atau mau coba ke luar negeri?";
        }

        // 3. Sapaan Ramah
        if (preg_match('/(halo|hai|pagi|siang|sore|malam|assalamualaikum|tes|test|loka)/u', $q)) {
            return "Halo! Ada yang bisa Loka bantu seputar info kursus LPK, sertifikasi BNSP, atau mau diskusi arah karir santai?";
        }

        // 4. Tanya Kursus / Katalog Pelatihan
        if (preg_match('/(kursus|pelatihan|kelas|katalog|program|training|belajar)/u', $q)) {
            return "Di Skilloka ada banyak program pelatihan kejuruan langsung praktik: teknik mesin, las, komputer & desain, bahasa Jepang kerja, perhotelan, sampai tata boga.

"
                . "Lengkap dengan bengkel praktik dan sertifikat kelulusan. Kamu lagi cari kursus di bidang apa nih?";
        }

        // 5. Cara Daftar / Booking
        if (str_contains($q, 'cara daftar') || str_contains($q, 'cara booking') || str_contains($q, 'cara ikut') || str_contains($q, 'daftar kursus')) {
            return "Daftarnya praktis banget:

"
                . "1. Buka katalog kursus di Skilloka dan pilih yang kamu mau.
"
                . "2. Klik **Daftar Sekarang** dan lengkapi data diri.
"
                . "3. Selesaikan pembayaran via Transfer Bank atau QRIS.

"
                . "Bukti pendaftaran resmi langsung masuk ke akunmu. Ada kendala pas daftar?";
        }

        // 6. Pembayaran & Biaya
        if (preg_match('/(bayar|pembayaran|biaya|harga|transfer|qris|midtrans)/u', $q)) {
            return "Pembayaran di Skilloka otomatis & aman via Midtrans. Bisa pakai QRIS (BCA, GoPay, OVO, Dana) atau Transfer Virtual Account bank.

"
                . "Semua transaksi terverifikasi instan dalam hitungan detik tanpa perlu upload struk manual.";
        }

        // 7. Sertifikasi BNSP
        if (preg_match('/(sertifikat|bnsp|ijazah|kelulusan|lisensi)/u', $q)) {
            return "Sertifikat BNSP adalah bukti kompetensi resmi berstandar nasional (logo Garuda) yang diakui perusahaan.

"
                . "Di Skilloka, setelah selesai pelatihan di LPK, kamu akan dibimbing langsung sampai siap ikut uji kompetensi BNSP. Ada bidang sertifikasi tertentu yang lagi kamu minati?";
        }

        // 8. Default Answer
        return "Pertanyaan menarik! Di Skilloka kamu bisa cari pelatihan vokasi resmi yang kurikulumnya langsung praktek kerja dan bersertifikat BNSP.

"
            . "Biar lebih pas, kamu lagi cari info kursus di bidang apa nih?";
    }

    /**
     * Build formatted string of active courses for AI prompt context
     */
    protected function buildCourseContext(): string
    {
        try {
            $courses = Course::with('lpk', 'category')->take(10)->get();
            if ($courses->isEmpty()) {
                return 'Belum ada kursus aktif.';
            }

            $list = [];
            foreach ($courses as $c) {
                $cat = $c->category->name ?? 'Kejuruan';
                $lpk = $c->lpk->name ?? 'Mitra LPK';
                $price = number_format($c->price ?? 0, 0, ',', '.');
                $list[] = "- {$c->title} (Kategori: {$cat}, LPK: {$lpk}, Biaya: Rp {$price})";
            }
            return implode("
", $list);
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
                return 'Belum ada LPK terdaftar.';
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
