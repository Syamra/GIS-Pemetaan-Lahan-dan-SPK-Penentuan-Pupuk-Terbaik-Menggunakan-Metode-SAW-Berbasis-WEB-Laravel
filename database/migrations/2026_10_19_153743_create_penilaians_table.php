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
        Schema::create('penilaian', function (Blueprint $table) {
            $table->id('id_penilaian');
            $table->foreignId('id_lahan')->constrained('pemetaan_lahan', 'id_lahan')->onDelete('cascade');
            $table->foreignId('id_pupuk')->constrained('alternatif', 'id_pupuk')->onDelete('cascade');
            $table->foreignId('id_kriteria')->constrained('kriteria', 'id_kriteria')->onDelete('cascade');
            $table->decimal('nilai', 5, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaians');
    }
};
