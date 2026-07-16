<?php

namespace Database\Seeders;

use App\Models\Sepeda;
use Illuminate\Database\Seeder;

class SepedaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // --- 8 unit sesuai mockup katalog ---
            ['kode' => 'CB-001', 'nama' => 'Tren Grit Bike',           'kategori' => 'premium', 'harga_per_jam' => 12000, 'harga_3jam' => 30000, 'harga_6jam' => 55000, 'status' => 'tersedia'],
            ['kode' => 'CB-002', 'nama' => 'Federal Bike',             'kategori' => 'premium', 'harga_per_jam' => 10000, 'harga_3jam' => 25000, 'harga_6jam' => 45000, 'status' => 'tersedia'],
            ['kode' => 'CB-003', 'nama' => 'Specialized Stumpjumper',  'kategori' => 'premium', 'harga_per_jam' => 15000, 'harga_3jam' => 40000, 'harga_6jam' => 75000, 'status' => 'tersedia'],
            ['kode' => 'CB-004', 'nama' => 'GT Avalanche MTB',         'kategori' => 'premium', 'harga_per_jam' => 14000, 'harga_3jam' => 35000, 'harga_6jam' => 65000, 'status' => 'disewa'],
            ['kode' => 'CB-005', 'nama' => 'Federal City Cat',         'kategori' => 'standar', 'harga_per_jam' =>  7000, 'harga_3jam' => 18000, 'harga_6jam' => 30000, 'status' => 'tersedia'],
            ['kode' => 'CB-006', 'nama' => 'Polygon Sierra',           'kategori' => 'standar', 'harga_per_jam' =>  8000, 'harga_3jam' => 20000, 'harga_6jam' => 35000, 'status' => 'tersedia'],
            ['kode' => 'CB-007', 'nama' => 'United Bike',              'kategori' => 'standar', 'harga_per_jam' =>  7000, 'harga_3jam' => 18000, 'harga_6jam' => 30000, 'status' => 'tersedia'],
            ['kode' => 'CB-008', 'nama' => 'Federal City Bike',        'kategori' => 'standar', 'harga_per_jam' =>  6000, 'harga_3jam' => 15000, 'harga_6jam' => 25000, 'status' => 'tersedia'],

            // --- 8 unit tambahan biar total 16 (sesuai dashboard admin) ---
            ['kode' => 'CB-009', 'nama' => 'Velo Classic Pro',         'kategori' => 'premium', 'harga_per_jam' => 13000, 'harga_3jam' => 32000, 'harga_6jam' => 60000, 'status' => 'tersedia'],
            ['kode' => 'CB-010', 'nama' => 'Apex Mountain',            'kategori' => 'premium', 'harga_per_jam' => 16000, 'harga_3jam' => 42000, 'harga_6jam' => 80000, 'status' => 'disewa'],
            ['kode' => 'CB-011', 'nama' => 'Urban Explorer',           'kategori' => 'standar', 'harga_per_jam' =>  7500, 'harga_3jam' => 19000, 'harga_6jam' => 32000, 'status' => 'tersedia'],
            ['kode' => 'CB-012', 'nama' => 'City Cruiser',             'kategori' => 'standar', 'harga_per_jam' =>  6500, 'harga_3jam' => 16000, 'harga_6jam' => 28000, 'status' => 'tersedia'],
            ['kode' => 'CB-013', 'nama' => 'Heritage Cruiser',         'kategori' => 'standar', 'harga_per_jam' =>  7000, 'harga_3jam' => 18000, 'harga_6jam' => 30000, 'status' => 'disewa'],
            ['kode' => 'CB-014', 'nama' => 'Vintage Racer A1',         'kategori' => 'premium', 'harga_per_jam' => 11000, 'harga_3jam' => 28000, 'harga_6jam' => 50000, 'status' => 'disewa'],
            ['kode' => 'CB-015', 'nama' => 'Classic City Bike',        'kategori' => 'standar', 'harga_per_jam' =>  6000, 'harga_3jam' => 15000, 'harga_6jam' => 25000, 'status' => 'tersedia'],
            ['kode' => 'CB-016', 'nama' => 'Roadster Nine',            'kategori' => 'premium', 'harga_per_jam' => 12500, 'harga_3jam' => 31000, 'harga_6jam' => 58000, 'status' => 'disewa'],
        ];

        foreach ($data as $row) {
            Sepeda::updateOrCreate(
                ['kode' => $row['kode']],   // kalau kode-nya udah ada, di-update (bukan dobel)
                $row
            );
        }
    }
}