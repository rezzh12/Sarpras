<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrasaranasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prasaranas', function (Blueprint $table) {
            $table->id();
            $table->string('kode',10);
            $table->foreignId('ruangan_id')->constrained();
            $table->integer('jumlah_ada');
            $table->string('kondisi',10);
            $table->integer('jumlah_diperlukan');
            $table->string('keterangan',150);
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
        Schema::dropIfExists('prasaranas');
    }
}
