<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // Barang dari Supplier 1 (PT Paragon Technology and Innovation)
            ['barang_kode' => 'BRG001', 'barang_nama' => 'Wardah Lipstick', 'kategori_id' => 4, 'harga_beli' => 45000, 'harga_jual' => 60000],
            ['barang_kode' => 'BRG002', 'barang_nama' => 'Emina Sunscreen', 'kategori_id' => 4, 'harga_beli' => 35000, 'harga_jual' => 50000],
            ['barang_kode' => 'BRG003', 'barang_nama' => 'Make Over Foundation', 'kategori_id' => 4, 'harga_beli' => 90000, 'harga_jual' => 120000],
            ['barang_kode' => 'BRG004', 'barang_nama' => 'Wardah BB Cream', 'kategori_id' => 4, 'harga_beli' => 50000, 'harga_jual' => 75000],
            ['barang_kode' => 'BRG005', 'barang_nama' => 'Emina Lip Tint', 'kategori_id' => 4, 'harga_beli' => 40000, 'harga_jual' => 55000],

            // Barang dari Supplier 2 (PT Natural Malino Indonesia)
            ['barang_kode' => 'BRG006', 'barang_nama' => 'Detergen Cair Molto', 'kategori_id' => 2, 'harga_beli' => 25000, 'harga_jual' => 40000],
            ['barang_kode' => 'BRG007', 'barang_nama' => 'Sabun Mandi Lifebuoy', 'kategori_id' => 2, 'harga_beli' => 12000, 'harga_jual' => 18000],
            ['barang_kode' => 'BRG008', 'barang_nama' => 'Shampoo Sunsilk', 'kategori_id' => 2, 'harga_beli' => 20000, 'harga_jual' => 32000],
            ['barang_kode' => 'BRG009', 'barang_nama' => 'Pelembut Pakaian Downy', 'kategori_id' => 2, 'harga_beli' => 18000, 'harga_jual' => 28000],
            ['barang_kode' => 'BRG010', 'barang_nama' => 'Sabun Cuci Piring Sunlight', 'kategori_id' => 2, 'harga_beli' => 10000, 'harga_jual' => 15000],

            // Barang dari Supplier 3 (PT Indofood Sukses Makmur TBK)
            ['barang_kode' => 'BRG011', 'barang_nama' => 'Indomie Goreng', 'kategori_id' => 1, 'harga_beli' => 2500, 'harga_jual' => 3500],
            ['barang_kode' => 'BRG012', 'barang_nama' => 'Chitato Rasa Sapi Panggang', 'kategori_id' => 1, 'harga_beli' => 10000, 'harga_jual' => 15000],
            ['barang_kode' => 'BRG013', 'barang_nama' => 'Susu Kental Manis Frisian Flag', 'kategori_id' => 1, 'harga_beli' => 8000, 'harga_jual' => 12000],
            ['barang_kode' => 'BRG014', 'barang_nama' => 'Pop Mie Rasa Ayam', 'kategori_id' => 1, 'harga_beli' => 5000, 'harga_jual' => 7000],
            ['barang_kode' => 'BRG015', 'barang_nama' => 'Biskuit Marie Regal', 'kategori_id' => 1, 'harga_beli' => 18000, 'harga_jual' => 25000],
        ];

        DB::table('m_barang')->insert($data);
    }
}
