<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('lpks', function (Blueprint $table) {

        $table->string('status_verifikasi')
            ->default('pending');

    });
}


public function down()
{
    Schema::table('lpks', function (Blueprint $table) {

        $table->dropColumn('status_verifikasi');

    });
}

};
