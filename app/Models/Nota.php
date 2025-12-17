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

    public static function generateNextNoNota(string $tanggal)
    {
        $date = Carbon::createFromFormat('Y-m-d', $tanggal);

        $bulan = $date->format('m');
        $tahunFull = $date->format('Y');
        $tahun = $date->format('y');

        $count = self::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahunFull)
            ->where('status', '!=', 0)
            ->count();

        $urutan = $count + 1;

        return sprintf(
            'MJ/%s%s/%03d',
            $tahun,
            $bulan,
            $urutan
        );
    }

}
