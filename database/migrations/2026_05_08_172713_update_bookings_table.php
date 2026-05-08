<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | PAYMENT STATUS
            |--------------------------------------------------------------------------
            */
            $table->string('payment_status')
                ->default('unpaid')
                ->after('status');



            /*
            |--------------------------------------------------------------------------
            | SOURCE BOOKING
            |--------------------------------------------------------------------------
            */
            $table->string('source')
                ->nullable()
                ->after('payment_status');



            /*
            |--------------------------------------------------------------------------
            | CREATED BY
            |--------------------------------------------------------------------------
            */
            $table->uuid('created_by')
                ->nullable()
                ->after('source');



            /*
            |--------------------------------------------------------------------------
            | NOTES
            |--------------------------------------------------------------------------
            */
            $table->text('notes')
                ->nullable()
                ->after('expires_at');

        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {

            $table->dropColumn([
                'payment_status',
                'source',
                'created_by',
                'notes'
            ]);

        });
    }
};