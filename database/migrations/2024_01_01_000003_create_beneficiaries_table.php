<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->string('nik');
            $table->string('nama');
            $table->text('alamat')->nullable();
            $table->string('kelompok_tani')->nullable();
            $table->string('bidang');
            $table->string('jenis_bantuan');
            $table->integer('tahun');
            $table->integer('kuantitas')->default(1);
            $table->string('status')->default('terdaftar');
            $table->text('link')->nullable();
            $table->text('keterangan')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('beneficiaries');
    }
};