<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePinjamRuangansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pinjam_ruangans', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 10);
            $table->date('dari_tanggal');
            $table->date('sampai_tanggal');
            $table->string('kode_ruangan',10);
            $table->string('nama_ruangan',10);
            $table->string('peminjam',100);
            $table->string('keterangan',150);
            $table->smallInteger('status');
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
        Schema::dropIfExists('pinjam_ruangans');
    }
}
