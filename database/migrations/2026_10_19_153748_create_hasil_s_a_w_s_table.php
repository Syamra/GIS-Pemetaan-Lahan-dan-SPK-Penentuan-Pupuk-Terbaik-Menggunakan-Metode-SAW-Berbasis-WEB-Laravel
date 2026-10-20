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
        Schema::create('hasil_saw', function (Blueprint $table) {
            $table->id('id_hasil');
            $table->foreignId('id_lahan')->constrained('pemetaan_lahan', 'id_lahan')->onDelete('cascade');
            $table->foreignId('id_pupuk')->constrained('alternatif', 'id_pupuk')->onDelete('cascade');
            $table->decimal('nilai_preferensi', 8, 4);
            $table->integer('peringkat');
            $table->date('tgl_perhitungan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_s_a_w_s');
    }
};
