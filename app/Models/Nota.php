<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    protected $table = 'nota'; 
    protected $primaryKey = 'id'; 
    public $timestamps = true; 

    protected $fillable = [
        'id',
        'no_nota',
        'tanggal',
        'pembeli',
        'nama_toko',
        'alamat',
        'subtotal',
        'diskon_persen',
        'diskon_rupiah',
        'total_harga',
        'total_coly',
        'jt_tempo',
        'status',
        'cek',
        'print',
    ];

    public function details()
    {
        return $this->hasMany(DetailNota::class, 'nota_id', 'id');
    }

    public static function generateNextNoNota()
    {
        // Ambil bulan dan tahun sekarang → format YYMM
        $prefix = date('ym'); // contoh: 2508 (Agustus 2025)

        // Cari nota terakhir di bulan & tahun ini
        $lastNota = self::where('no_nota', 'like', "MJ/$prefix/%")
            ->latest('id')
            ->first();

        if (!$lastNota) {
            $nextNumber = 1;
        } else {
            // Ambil bagian nomor urut setelah prefix
            $lastNumber = (int) substr($lastNota->no_nota, -3);
            $nextNumber = $lastNumber + 1;
        }

        // Format: MJ/YYMM/XXX
        return "MJ/{$prefix}/" . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

}
