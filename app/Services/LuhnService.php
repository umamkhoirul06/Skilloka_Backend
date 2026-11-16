<?php

namespace App\Services;

class LuhnService
{
    /**
     * Memvalidasi apakah sebuah nomor/string numerik valid berdasarkan Algoritma Luhn (Modulus 10).
     *
     * @param string|int $number Nomor identitas, nomor kartu siswa, atau nomor kartu verifikasi
     * @return bool True jika check digit valid sesuai algoritma Luhn
     */
    public static function validate($number): bool
    {
        $clean = preg_replace('/[^\d]/', '', (string)$number);
        $length = strlen($clean);

        if ($length < 2) {
            return false;
        }

        $sum = 0;
        $parity = $length % 2;

        for ($i = 0; $i < $length; $i++) {
            $digit = (int)$clean[$i];

            // Lipat gandakan setiap digit kedua dari kanan
            if ($i % 2 === $parity) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }

            $sum += $digit;
        }

        return ($sum % 10) === 0;
    }

    /**
     * Menghitung check digit (digit ke-N terakhir) untuk angka dasar agar memenuhi Algoritma Luhn.
     *
     * @param string|int $partialNumber Nomor dasar tanpa check digit
     * @return int Check digit (0-9)
     */
    public static function generateCheckDigit($partialNumber): int
    {
        $clean = preg_replace('/[^\d]/', '', (string)$partialNumber);
        $length = strlen($clean);

        $sum = 0;
        // Paritas dihitung seolah-olah check digit sudah ada di posisi terakhir
        $parity = ($length + 1) % 2;

        for ($i = 0; $i < $length; $i++) {
            $digit = (int)$clean[$i];

            if ($i % 2 === $parity) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }

            $sum += $digit;
        }

        $mod = $sum % 10;
        return $mod === 0 ? 0 : (10 - $mod);
    }

    /**
     * Membuat nomor ID Kartu Siswa resmi berbasis Luhn Checksum.
     * Format: [Tahun 4 digit][Kode Vokasi 2 digit][Sequence 4 digit][Luhn Check Digit 1 digit]
     * Contoh: 2026 01 0045 [X]
     *
     * @param int|string $seed
     * @param int $vocationCode
     * @return string Nomor kartu ber-check digit yang selalu valid Luhn
     */
    public static function generateStudentCardNumber($seed = 1, int $vocationCode = 10): string
    {
        $year = date('Y');
        $code = str_pad((string)$vocationCode, 2, '0', STR_PAD_LEFT);
        
        // Ambil representasi angka dari seed (jika UUID, ambil representasi CRC32)
        if (is_numeric($seed)) {
            $seq = str_pad((string)abs((int)$seed % 100000), 5, '0', STR_PAD_LEFT);
        } else {
            $seq = str_pad((string)(abs(crc32((string)$seed)) % 100000), 5, '0', STR_PAD_LEFT);
        }

        $base = "{$year}{$code}{$seq}";
        $checkDigit = self::generateCheckDigit($base);

        return "{$base}{$checkDigit}";
    }

    /**
     * Mendeteksi apakah input login merupakan format nomor kartu identitas / siswa
     */
    public static function isCardIdentifier(string $input): bool
    {
        $clean = preg_replace('/[^\d]/', '', $input);
        // Panjang nomor identitas siswa/kartu peserta umumnya 10 - 16 digit
        return strlen($clean) >= 10 && strlen($clean) <= 16 && !str_contains($input, '@');
    }
}
