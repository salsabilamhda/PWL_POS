<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PenjualanDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // Transaksi PNJ001
            ['penjualan_id' => 1, 'barang_id' => 1, 'harga' => 60000, 'jumlah' => 2],
            ['penjualan_id' => 1, 'barang_id' => 2, 'harga' => 50000, 'jumlah' => 1],
            ['penjualan_id' => 1, 'barang_id' => 3, 'harga' => 120000, 'jumlah' => 3],

            // Transaksi PNJ002
            ['penjualan_id' => 2, 'barang_id' => 4, 'harga' => 75000, 'jumlah' => 2],
            ['penjualan_id' => 2, 'barang_id' => 5, 'harga' => 55000, 'jumlah' => 1],
            ['penjualan_id' => 2, 'barang_id' => 6, 'harga' => 40000, 'jumlah' => 3],

            // Transaksi PNJ003
            ['penjualan_id' => 3, 'barang_id' => 7, 'harga' => 18000, 'jumlah' => 1],
            ['penjualan_id' => 3, 'barang_id' => 8, 'harga' => 32000, 'jumlah' => 2],
            ['penjualan_id' => 3, 'barang_id' => 9, 'harga' => 28000, 'jumlah' => 1],

            // Transaksi PNJ004
            ['penjualan_id' => 4, 'barang_id' => 10, 'harga' => 15000, 'jumlah' => 3],
            ['penjualan_id' => 4, 'barang_id' => 11, 'harga' => 3500, 'jumlah' => 5],
            ['penjualan_id' => 4, 'barang_id' => 12, 'harga' => 15000, 'jumlah' => 2],

            // Transaksi PNJ005
            ['penjualan_id' => 5, 'barang_id' => 13, 'harga' => 12000, 'jumlah' => 4],
            ['penjualan_id' => 5, 'barang_id' => 14, 'harga' => 7000, 'jumlah' => 3],
            ['penjualan_id' => 5, 'barang_id' => 15, 'harga' => 25000, 'jumlah' => 1],

            // Transaksi PNJ006
            ['penjualan_id' => 6, 'barang_id' => 1, 'harga' => 60000, 'jumlah' => 2],
            ['penjualan_id' => 6, 'barang_id' => 5, 'harga' => 55000, 'jumlah' => 1],
            ['penjualan_id' => 6, 'barang_id' => 10, 'harga' => 15000, 'jumlah' => 3],

            // Transaksi PNJ007
            ['penjualan_id' => 7, 'barang_id' => 2, 'harga' => 50000, 'jumlah' => 2],
            ['penjualan_id' => 7, 'barang_id' => 6, 'harga' => 40000, 'jumlah' => 1],
            ['penjualan_id' => 7, 'barang_id' => 11, 'harga' => 3500, 'jumlah' => 5],

            // Transaksi PNJ008
            ['penjualan_id' => 8, 'barang_id' => 3, 'harga' => 120000, 'jumlah' => 1],
            ['penjualan_id' => 8, 'barang_id' => 7, 'harga' => 18000, 'jumlah' => 3],
            ['penjualan_id' => 8, 'barang_id' => 12, 'harga' => 15000, 'jumlah' => 2],

            // Transaksi PNJ009
            ['penjualan_id' => 9, 'barang_id' => 4, 'harga' => 75000, 'jumlah' => 2],
            ['penjualan_id' => 9, 'barang_id' => 9, 'harga' => 28000, 'jumlah' => 1],
            ['penjualan_id' => 9, 'barang_id' => 13, 'harga' => 12000, 'jumlah' => 4],

            // Transaksi PNJ010
            ['penjualan_id' => 10, 'barang_id' => 8, 'harga' => 32000, 'jumlah' => 2],
            ['penjualan_id' => 10, 'barang_id' => 14, 'harga' => 7000, 'jumlah' => 3],
            ['penjualan_id' => 10, 'barang_id' => 15, 'harga' => 25000, 'jumlah' => 1],
        ];

        DB::table('t_penjualan_detail')->insert($data);
    }
}
