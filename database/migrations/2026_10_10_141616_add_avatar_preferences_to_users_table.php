
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan preferensi avatar dan frame pengguna.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar_type', 20)
                ->default('photo')
                ->after('photo');

            $table->string('avatar_id', 50)
                ->nullable()
                ->after('avatar_type');

            $table->string('frame_id', 20)
                ->default('none')
                ->after('avatar_id');
        });
    }

    /**
     * Menghapus kolom preferensi avatar jika migration dibatalkan.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'avatar_type',
                'avatar_id',
                'frame_id',
            ]);
        });
    }
};