<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStaticAttributeToPendaftaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->string('jurusan')->after('pas_foto');
            $table->string('fakultas')->after('jurusan');
            $table->string('universitas')->after('fakultas');
        });

        Schema::table('beasiswa', function (Blueprint $table) {
            $table->string('penyelenggara', 20)->after('tanggal_selesai');
            $table->foreign('penyelenggara')->references('username')->on('user')
                ->nullable()->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->dropColumn('jurusan');
            $table->dropColumn('fakultas');
            $table->dropColumn('universitas');
        });
    }
}
