<?php

namespace Database\Seeders;

use App\Models\Sepeda;
use Illuminate\Database\Seeder;

class SepedaSeeder extends Seeder
{
    public function run(): void
    {
        // 8 model sepeda sesuai mockup Katalog.
        // Total stok = 16 unit, sesuai angka di Beranda & Dashboard Admin.
        // Tipe & stok mengikuti mockup halaman Pesan.
        $data = [
            ['kode' => 'CB-001', 'nama' => 'Tren Grit Bike',          'tipe' => 'Gravel Bike',   'kategori' => 'premium', 'stok' => 2, 'harga_per_jam' => 12000, 'harga_3jam' => 30000, 'harga_6jam' => 55000],
            ['kode' => 'CB-002', 'nama' => 'Federal Bike',            'tipe' => 'City Bike',     'kategori' => 'premium', 'stok' => 2, 'harga_per_jam' => 10000, 'harga_3jam' => 25000, 'harga_6jam' => 45000],
            ['kode' => 'CB-003', 'nama' => 'Specialized Stumpjumper', 'tipe' => 'Mountain Bike', 'kategori' => 'premium', 'stok' => 3, 'harga_per_jam' => 15000, 'harga_3jam' => 40000, 'harga_6jam' => 75000],
            ['kode' => 'CB-004', 'nama' => 'GT Avalanche MTB',        'tipe' => 'Mountain Bike', 'kategori' => 'premium', 'stok' => 0, 'harga_per_jam' => 14000, 'harga_3jam' => 35000, 'harga_6jam' => 65000],
            ['kode' => 'CB-005', 'nama' => 'Federal City Cat',        'tipe' => 'City Bike',     'kategori' => 'standar', 'stok' => 2, 'harga_per_jam' =>  7000, 'harga_3jam' => 18000, 'harga_6jam' => 30000],
            ['kode' => 'CB-006', 'nama' => 'Polygon Sierra',          'tipe' => 'Folding Bike',  'kategori' => 'standar', 'stok' => 1, 'harga_per_jam' =>  8000, 'harga_3jam' => 20000, 'harga_6jam' => 35000],
            ['kode' => 'CB-007', 'nama' => 'United Bike',             'tipe' => 'Mountain Bike', 'kategori' => 'standar', 'stok' => 3, 'harga_per_jam' =>  7000, 'harga_3jam' => 18000, 'harga_6jam' => 30000],
            ['kode' => 'CB-008', 'nama' => 'Federal City Bike',       'tipe' => 'Folding Bike',  'kategori' => 'standar', 'stok' => 3, 'harga_per_jam' =>  6000, 'harga_3jam' => 15000, 'harga_6jam' => 25000],
        ];

        foreach ($data as $row) {
            Sepeda::updateOrCreate(
                ['kode' => $row['kode']],   // kalau kode-nya udah ada, di-update (bukan dobel)
                $row
            );
        }

        // Hapus CB-009 s/d CB-016 dari seeder sebelumnya.
        // Sepeda itu cuma data karangan, nggak ada di mockup manapun.
        Sepeda::whereNotIn('kode', array_column($data, 'kode'))->delete();
    }
}