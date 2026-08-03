<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('services')->truncate();

        DB::table('services')->insert([
            ['name' => 'Sphygmomanometer/Tensimeter',         'price' => 84000,  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pulse Oximetri (SPO2 Monitor)',        'price' => 180000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Nebulizer',                            'price' => 228000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Blood Pressure Monitor (BPM)/Non',    'price' => 162000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Regulator Oksigen (Flow Meter)',       'price' => 192000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Timbangan Bayi',                       'price' => 180000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Fetal Detector/Doppler',               'price' => 156000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Suction Pump/Alat Hisap Medik',       'price' => 264000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ECG (Electrocardiograph) Monitor',    'price' => 168000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Centrifuge',                           'price' => 240000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ultrasonography (USG)',                'price' => 300000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Autoclave',                            'price' => 312000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Infant Warmer',                        'price' => 240000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Inkubator Perawatan',                  'price' => 324000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Monitor Pasien (Bed Side Monitor)',    'price' => 588000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sterilisator Kering',                  'price' => 204000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Photo Therapy Unit/Blue Light',        'price' => 204000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lampu Operasi',                        'price' => 192000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cardiocotograph (CTG)',                'price' => 168000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Defibrillator Monitor',                'price' => 300000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Infusion Pump',                        'price' => 288000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Syringe Pump',                         'price' => 288000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Spirometer',                           'price' => 168000, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Continuous Positive Airways (CPAP)',   'price' => 396000, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
