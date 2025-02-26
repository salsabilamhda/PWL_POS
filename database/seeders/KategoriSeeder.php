<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kategori_kode' => 'FB', 'kategori_nama' => 'Food & Beverage'],
            ['kategori_kode' => 'HC', 'kategori_nama' => 'Home Care'],
            ['kategori_kode' => 'BK', 'kategori_nama' => 'Baby & Kid'],
            ['kategori_kode' => 'BH', 'kategori_nama' => 'Beauty & Health'],
            ['kategori_kode' => 'FA', 'kategori_nama' => 'Fashion'],
        ];

        // **Cek apakah data sudah ada sebelum insert**
        foreach ($data as $item) {
            DB::table('m_kategori')->updateOrInsert(
                ['kategori_kode' => $item['kategori_kode']], // Kondisi unik
                ['kategori_nama' => $item['kategori_nama']]  // Data yang diperbarui
            );
        }
    }
}