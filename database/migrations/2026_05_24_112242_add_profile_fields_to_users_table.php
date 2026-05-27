<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('address')->nullable()->after('email');
            $table->enum('gender', ['male', 'female'])->nullable()->after('address');
            $table->date('birth_date')->nullable()->after('gender');
            $table->string('photo')->nullable()->after('birth_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Kolom 'email' juga dihapus dari daftar drop agar aman saat di-rollback
            $table->dropColumn(['address', 'gender', 'birth_date', 'photo']);
        });
    }
};