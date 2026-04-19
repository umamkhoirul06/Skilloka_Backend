<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
{
    Schema::table('tenants', function (Blueprint $table) {

        /*
        CONTACT
        */

        if (!Schema::hasColumn('tenants', 'phone')) {
            $table->string('phone')->nullable();
        }

        if (!Schema::hasColumn('tenants', 'email')) {
            $table->string('email')->nullable();
        }

        if (!Schema::hasColumn('tenants', 'website')) {
            $table->string('website')->nullable();
        }

        if (!Schema::hasColumn('tenants', 'instagram')) {
            $table->string('instagram')->nullable();
        }

        if (!Schema::hasColumn('tenants', 'facebook')) {
            $table->string('facebook')->nullable();
        }

        if (!Schema::hasColumn('tenants', 'tiktok')) {
            $table->string('tiktok')->nullable();
        }



        /*
        LOCATION
        */

        if (!Schema::hasColumn('tenants', 'province')) {
            $table->string('province')->nullable();
        }

        if (!Schema::hasColumn('tenants', 'city')) {
            $table->string('city')->nullable();
        }

        if (!Schema::hasColumn('tenants', 'district')) {
            $table->string('district')->nullable();
        }

        if (!Schema::hasColumn('tenants', 'address')) {
            $table->text('address')->nullable();
        }

        if (!Schema::hasColumn('tenants', 'latitude')) {
            $table->decimal('latitude',10,7)->nullable();
        }

        if (!Schema::hasColumn('tenants', 'longitude')) {
            $table->decimal('longitude',10,7)->nullable();
        }



        /*
        MEDIA
        */

        if (!Schema::hasColumn('tenants', 'logo')) {
            $table->string('logo')->nullable();
        }

        if (!Schema::hasColumn('tenants', 'banner')) {
            $table->string('banner')->nullable();
        }



        /*
        FACILITIES
        */

        if (!Schema::hasColumn('tenants', 'facilities')) {
            $table->text('facilities')->nullable();
        }

    });
}

};