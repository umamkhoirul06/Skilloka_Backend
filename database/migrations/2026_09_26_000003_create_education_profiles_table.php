<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('education_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('education_level'); // SMA, SMK, PERGURUAN_TINGGI, LAINNYA
            $table->string('major');
            $table->string('institution_name')->nullable();
            $table->integer('graduation_year')->nullable();
            $table->string('career_goal')->nullable();
            $table->string('preferred_location')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('education_profiles');
    }
};
