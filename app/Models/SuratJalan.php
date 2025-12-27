<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratJalan extends Model
{
    protected $table = 'surat_jalan'; 
    public $timestamps = false; 

    protected $fillable = [
        'id',
        'no_surat',
        'tanggal',
        'nama_toko',
        'alamat',
        'kendaraan',
        'no_kendaraan',        
        'total_coly',
        'nota',
        'print',
        'status',
    ];

        public function detailsj()
    {
        return $this->hasMany(DetailSuratJalan::class, 's_jalan_id', 'id');
    }

    public static function generateNextNoSuratJalan()
    {
        // Ambil bulan dan tahun sekarang → format YYMM
        $prefix = date('ym'); // contoh: 2508 (Agustus 2025)

        // Cari nota terakhir di bulan & tahun ini
        $lastSurat = self::where('no_surat', 'like', "SJ/$prefix/%")
            ->latest('id')
            ->first();

        if (!$lastSurat) {
            $nextNumber = 1;
        } else {
            // Ambil bagian nomor urut setelah prefix
            $lastNumber = (int) substr($lastSurat->no_surat, -3);
            $nextNumber = $lastNumber + 1;
        }

        // Format: MJ/YYMM/XXX
        return "SJ/{$prefix}/" . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

}
