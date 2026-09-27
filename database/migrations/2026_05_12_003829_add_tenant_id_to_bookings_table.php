<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasTable('bookings')) {
            Schema::create('bookings', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
                $table->foreignUuid('course_id')->constrained()->cascadeOnDelete();
                $table->string('code')->nullable();
                $table->decimal('amount', 15, 2)->default(0);
                $table->string('status')->default('pending');
                $table->string('snap_token')->nullable();
                $table->string('payment_method')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('bookings', function (Blueprint $table) {
                if (!Schema::hasColumn('bookings', 'tenant_id')) {
                    $table->foreignUuid('tenant_id')->nullable()->constrained('tenants')->nullOnDelete();
                }
                if (!Schema::hasColumn('bookings', 'code')) {
                    $table->string('code')->nullable();
                }
                if (!Schema::hasColumn('bookings', 'amount')) {
                    $table->decimal('amount', 15, 2)->default(0);
                }
                if (!Schema::hasColumn('bookings', 'snap_token')) {
                    $table->string('snap_token')->nullable();
                }
                if (!Schema::hasColumn('bookings', 'payment_method')) {
                    $table->string('payment_method')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'tenant_id')) {
                $table->dropForeign(['tenant_id']);
                $table->dropColumn('tenant_id');
            }
        });
    }
};
