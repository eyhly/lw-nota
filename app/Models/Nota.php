<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    public static function generateNextNoNota($tanggal)
    {
        $date = \Carbon\Carbon::parse($tanggal);
        
        // Format: MJ/YYMM/NNN
        $yearMonth = $date->format('ym'); // 2512 untuk Des 2025
        $prefix = 'MJ/' . $yearMonth . '/';
        
        // Cari nomor terakhir di bulan yang sama
        $lastNota = self::where('no_nota', 'LIKE', $prefix . '%')
                        ->orderByRaw('CAST(SUBSTRING(no_nota, -3) AS UNSIGNED) DESC')
                        ->first();
        
        if ($lastNota) {
            // Ambil 3 digit terakhir
            $lastNumber = (int) substr($lastNota->no_nota, -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

}
