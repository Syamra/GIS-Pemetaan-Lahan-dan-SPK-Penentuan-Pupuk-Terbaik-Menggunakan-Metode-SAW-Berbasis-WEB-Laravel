<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pemilik_lahan', function (Blueprint $table) {
            $table->id('id_pemilik');
            $table->string('nama_pemilik', 50);
            $table->string('alamat', 200);
            $table->string('no_hp', 15);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pemilik_lahan');
    }
};