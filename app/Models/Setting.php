<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'sertifikat_kan',
        'surat_operasional',
        'spreadsheet_url',
    ];

    /**
     * Ambil satu-satunya baris pengaturan (dibuat otomatis kalau belum ada).
     */
    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1]);
    }
}