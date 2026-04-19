<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {

        Schema::create('settings', function (Blueprint $table) {

            $table->id();

            // pengaturan platform
            $table->string('platform_name')->nullable();

            $table->string('support_email')->nullable();

            $table->string('timezone')->default('Asia/Jakarta');

            $table->string('language')->default('Indonesia');


            // pengaturan pembayaran
            $table->integer('platform_fee')->default(10);

            $table->string('payment_method')->default('Transfer Bank');


            $table->timestamps();

        });

    }



    public function down(): void
    {

        Schema::dropIfExists('settings');

    }

};