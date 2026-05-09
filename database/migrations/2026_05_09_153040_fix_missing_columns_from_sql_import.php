<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambal tabel users
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status', 50)->default('pending')->nullable();
            }
            if (!Schema::hasColumn('users', 'phone_number')) {
                $table->string('phone_number')->nullable()->unique();
            }
        });

        // 2. Tambal tabel lpks
        Schema::table('lpks', function (Blueprint $table) {
            if (!Schema::hasColumn('lpks', 'is_verified')) {
                $table->boolean('is_verified')->default(false);
            }
            if (!Schema::hasColumn('lpks', 'status')) {
                $table->string('status')->default('pending');
            }
            if (!Schema::hasColumn('lpks', 'status_verifikasi')) {
                $table->string('status_verifikasi')->default('pending');
            }
        });

        // 3. Tambal tabel tenants
        Schema::table('tenants', function (Blueprint $table) {
            if (!Schema::hasColumn('tenants', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Fitur rollback dikosongkan agar aman
    }
};