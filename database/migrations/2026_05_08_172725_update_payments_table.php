<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | PAYMENT PROOF
            |--------------------------------------------------------------------------
            */
            $table->string('proof')
                ->nullable()
                ->after('provider');



            /*
            |--------------------------------------------------------------------------
            | VERIFIED BY
            |--------------------------------------------------------------------------
            */
            $table->uuid('verified_by')
                ->nullable()
                ->after('paid_at');



            /*
            |--------------------------------------------------------------------------
            | VERIFIED AT
            |--------------------------------------------------------------------------
            */
            $table->timestamp('verified_at')
                ->nullable()
                ->after('verified_by');



            /*
            |--------------------------------------------------------------------------
            | NOTES
            |--------------------------------------------------------------------------
            */
            $table->text('notes')
                ->nullable()
                ->after('metadata');

        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {

            $table->dropColumn([
                'proof',
                'verified_by',
                'verified_at',
                'notes'
            ]);

        });
    }
};