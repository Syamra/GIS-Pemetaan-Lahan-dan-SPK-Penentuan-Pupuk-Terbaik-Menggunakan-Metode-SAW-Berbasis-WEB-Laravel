<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SawSeeder extends Seeder
{
    public function run()
    {
        // 1. Data Kriteria (Sesuai kesepakatan: C4 Harga pakai skala 1-5, jadi atributnya benefit)
        DB::table('kriteria')->insert([
            ['kode' => 'C1', 'nama_kriteria' => 'Jenis Tanah', 'atribut' => 'benefit', 'bobot' => 25],
            ['kode' => 'C2', 'nama_kriteria' => 'pH Tanah', 'atribut' => 'benefit', 'bobot' => 35],
            ['kode' => 'C3', 'nama_kriteria' => 'Umur Tanaman', 'atribut' => 'benefit', 'bobot' => 25],
            ['kode' => 'C4', 'nama_kriteria' => 'Harga Pupuk', 'atribut' => 'benefit', 'bobot' => 15],
        ]);

        // 2. Data Alternatif Pupuk
        DB::table('alternatif')->insert([
            ['kode' => 'A1', 'nama_pupuk' => 'NPK Mutiara', 'harga' => 700000],
            ['kode' => 'A2', 'nama_pupuk' => 'NPK Mahkota', 'harga' => 800000],
            ['kode' => 'A3', 'nama_pupuk' => 'Dolomit', 'harga' => 35000],
            ['kode' => 'A4', 'nama_pupuk' => 'Pupuk Organik', 'harga' => 305000],
            ['kode' => 'A5', 'nama_pupuk' => 'Urea', 'harga' => 600000],
            ['kode' => 'A6', 'nama_pupuk' => 'ZA Granul', 'harga' => 495000],
            ['kode' => 'A7', 'nama_pupuk' => 'KCL', 'harga' => 700000],
            ['kode' => 'A8', 'nama_pupuk' => 'Phonska', 'harga' => 250000],
        ]);

    }
}