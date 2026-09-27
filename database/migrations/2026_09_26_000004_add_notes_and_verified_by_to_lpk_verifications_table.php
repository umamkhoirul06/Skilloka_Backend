<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('lpk_verifications', function (Blueprint $table) {
            if (!Schema::hasColumn('lpk_verifications', 'notes')) {
                $table->text('notes')->nullable()->after('status');
            }
            if (!Schema::hasColumn('lpk_verifications', 'verified_by')) {
                $table->foreignUuid('verified_by')->nullable()->after('notes')->constrained('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('lpk_verifications', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn(['notes', 'verified_by']);
        });
    }
};
