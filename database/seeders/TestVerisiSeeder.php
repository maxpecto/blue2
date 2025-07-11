<?php

namespace Database\Seeders;

use App\Models\Courier;
use App\Models\Investor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestVerisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Investor::create([
            'ad' => 'Kamran',
            'soyad' => 'Əliyev',
            'fin_kodu' => '1ABCDEF',
            'seria_no' => 'AZ1234567',
            'dogum_tarihi' => '1985-05-15',
            'email' => 'kamran.aliyev@invest.az',
            'telefon' => '+994501234567',
        ]);

        Courier::create([
            'ad' => 'Elgün',
            'soyad' => 'Ağayev',
            'ata_adi' => 'Rəşid',
            'seria_no' => 'AA1234567',
            'fin_kodu' => '5AB1CDE',
            'dogum_tarihi' => '1990-02-20',
            'telefon_no' => '+994559876543',
            'adres_1' => 'Bakı, 28 May küç. 15',
            'akraba_tel_1' => '+994551112233',
            'status' => 'aktif',
        ]);
    }
}
