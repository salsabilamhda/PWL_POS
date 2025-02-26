<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['supplier_kode' => 'SUP001', 'supplier_nama' => 'PT Paragon Technology and Innovation', 'supplier_alamat' => 'Jakarta'],
            ['supplier_kode' => 'SUP002', 'supplier_nama' => 'PT Natural Malino Indonesia', 'supplier_alamat' => 'Bekasi'],
            ['supplier_kode' => 'SUP003', 'supplier_nama' => 'PT Indofood Sukses Makmur TBK', 'supplier_alamat' => 'Pasuruan'],
        ];
        DB::table('m_supplier')->insert($data);
    }
}