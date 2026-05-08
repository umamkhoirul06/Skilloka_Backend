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
        Schema::create('transactions', function (Blueprint $table) {

            $table->uuid('id')->primary();

            // user yang melakukan transaksi
            $table->foreignUuid('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // tenant / LPK pemilik transaksi
            $table->foreignUuid('tenant_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // nomor invoice
            $table->string('invoice')->unique();

            // nominal pembayaran
            $table->decimal('amount', 12, 2);

            // status transaksi
            $table->enum('status', [
                'pending',
                'paid',
                'failed'
            ])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};