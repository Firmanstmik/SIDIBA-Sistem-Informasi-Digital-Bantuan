<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('monevs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beneficiary_id')->constrained()->onDelete('cascade');
            $table->date('tanggal_monev');
            $table->string('pelaksana');
            $table->text('hasil_evaluasi')->nullable();
            $table->string('rekomendasi')->nullable();
            $table->string('dokumentasi')->nullable();
            $table->string('status')->default('selesai');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('monevs');
    }
};