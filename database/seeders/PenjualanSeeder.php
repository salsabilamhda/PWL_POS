<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'user_id' => 1,
                'pembeli' => 'Azzahra Attaqina',
                'penjualan_kode' => 'PNJ001',
                'penjualan_tanggal' => Carbon::now()->subDays(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'pembeli' => 'Ericha Rizki',
                'penjualan_kode' => 'PNJ002',
                'penjualan_tanggal' => Carbon::now()->subDays(9),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'pembeli' => 'Valentina Santi',
                'penjualan_kode' => 'PNJ003',
                'penjualan_tanggal' => Carbon::now()->subDays(8),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'pembeli' => 'Sadiya Martiza',
                'penjualan_kode' => 'PNJ004',
                'penjualan_tanggal' => Carbon::now()->subDays(7),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'pembeli' => 'Nur Ayu',
                'penjualan_kode' => 'PNJ005',
                'penjualan_tanggal' => Carbon::now()->subDays(6),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'pembeli' => 'Dedy Bayu',
                'penjualan_kode' => 'PNJ006',
                'penjualan_tanggal' => Carbon::now()->subDays(5),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'pembeli' => 'Rizqi Fauzan',
                'penjualan_kode' => 'PNJ007',
                'penjualan_tanggal' => Carbon::now()->subDays(4),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'pembeli' => 'Zilan Zalilan',
                'penjualan_kode' => 'PNJ008',
                'penjualan_tanggal' => Carbon::now()->subDays(3),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'pembeli' => 'Aryan Saputra',
                'penjualan_kode' => 'PNJ009',
                'penjualan_tanggal' => Carbon::now()->subDays(2),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'pembeli' => 'Michel Dorani',
                'penjualan_kode' => 'PNJ010',
                'penjualan_tanggal' => Carbon::now()->subDays(1),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('t_penjualan')->insert($data);
    }
}
