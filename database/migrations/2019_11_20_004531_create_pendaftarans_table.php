<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendaftaransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pendaftarans', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('username', 20);
            $table->foreign('username')->references('username')->on('user')->nullable()->onDelete('cascade')->onUpdate('cascade');
            $table->string('id_beasiswa', 36);
            $table->foreign('id_beasiswa')->references('id')->on('beasiswa')->nullable()->onDelete('cascade')->onUpdate('cascade');
            $table->string('alamat', 100);
            $table->string('nomor_telepon', 20);
            $table->float('ipk');
            $table->string('transkip_nilai');
            $table->string('pas_foto');
            $table->unsignedTinyInteger('verifikasi')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pendaftarans');
    }
}
