<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lpk_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('lpk_id')->constrained('lpks')->cascadeOnDelete();
            $table->foreignId('verified_by')->constrained('users');
            $table->enum('type', ['dinas', 'internal']);
            $table->text('notes')->nullable();
            $table->json('documents')->nullable();
            $table->timestamp('verified_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lpk_verifications');
    }
};
