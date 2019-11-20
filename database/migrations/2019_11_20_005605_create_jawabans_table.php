<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJawabansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jawabans', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('id_soal', 36);
            $table->foreign('id_soal')->references('id')->on('pertanyaans')->nullable()->onDelete('cascade')->onUpdate('cascade');
            $table->string('username', 20);
            $table->foreign('username')->references('username')->on('user')->nullable()->onDelete('cascade')->onUpdate('cascade');
            $table->text('text_jawaban');
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
        Schema::dropIfExists('jawabans');
    }
}
