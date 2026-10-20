<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('pemetaan_lahan', function (Blueprint $table) {
            $table->id('id_lahan');
            $table->decimal('luas_lahan', 10, 2);
            $table->text('batas_polygon');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemetaan_lahans');
    }
};
