<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePertanyaansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pertanyaans', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('id_beasiswa', 36);
            $table->foreign('id_beasiswa')->references('id')->on('beasiswa')->nullable()->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedSmallInteger('index_soal');
            $table->text('text_soal');
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
        Schema::dropIfExists('pertanyaans');
    }
}
