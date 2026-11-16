<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Hanya jalankan jika menggunakan PostgreSQL
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        // 1. Aktifkan ekstensi pg_trgm untuk toleransi typo & fuzzy matching
        DB::statement('CREATE EXTENSION IF NOT EXISTS pg_trgm;');

        // 2. Pastikan text search configuration 'indonesian' tersedia (fallback ke simple jika belum ada di distro OS)
        DB::statement("
            DO $$
            BEGIN
                IF NOT EXISTS (SELECT 1 FROM pg_ts_config WHERE cfgname = 'indonesian') THEN
                    CREATE TEXT SEARCH CONFIGURATION indonesian (COPY = simple);
                END IF;
            END
            $$;
        ");

        // 3. Tambahkan Indeks GIN Full-Text Search dan Trigram pada tabel courses
        DB::statement("
            CREATE INDEX IF NOT EXISTS courses_fts_idx 
            ON courses 
            USING gin (to_tsvector('indonesian', coalesce(title, '') || ' ' || coalesce(description, '') || ' ' || coalesce(syllabus, '')));
        ");

        DB::statement("
            CREATE INDEX IF NOT EXISTS courses_title_trgm_idx 
            ON courses 
            USING gin (title gin_trgm_ops);
        ");

        DB::statement("
            CREATE INDEX IF NOT EXISTS courses_desc_trgm_idx 
            ON courses 
            USING gin (description gin_trgm_ops);
        ");

        // 4. Tambahkan Indeks GIN Full-Text Search dan Trigram pada tabel lpks
        DB::statement("
            CREATE INDEX IF NOT EXISTS lpks_fts_idx 
            ON lpks 
            USING gin (to_tsvector('indonesian', coalesce(name, '') || ' ' || coalesce(description, '')));
        ");

        DB::statement("
            CREATE INDEX IF NOT EXISTS lpks_name_trgm_idx 
            ON lpks 
            USING gin (name gin_trgm_ops);
        ");

        DB::statement("
            CREATE INDEX IF NOT EXISTS lpks_desc_trgm_idx 
            ON lpks 
            USING gin (description gin_trgm_ops);
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        DB::statement('DROP INDEX IF EXISTS courses_fts_idx;');
        DB::statement('DROP INDEX IF EXISTS courses_title_trgm_idx;');
        DB::statement('DROP INDEX IF EXISTS courses_desc_trgm_idx;');

        DB::statement('DROP INDEX IF EXISTS lpks_fts_idx;');
        DB::statement('DROP INDEX IF EXISTS lpks_name_trgm_idx;');
        DB::statement('DROP INDEX IF EXISTS lpks_desc_trgm_idx;');
    }
};
