<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('lpks', function (Blueprint $table) {
            if (!Schema::hasColumn('lpks', 'phone')) {
                $table->string('phone')->nullable();
            }
            if (!Schema::hasColumn('lpks', 'wa_number')) {
                $table->string('wa_number')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('lpks', function (Blueprint $table) {
            $table->dropColumn(['phone', 'wa_number']);
        });
    }
};
