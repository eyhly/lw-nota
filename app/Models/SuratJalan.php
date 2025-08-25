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
        'pembeli',
        'kendaraan',
        'no_kendaraan',        
        'total_coly',
        'status',
    ];

        public function detailsj()
    {
        return $this->hasMany(DetailSuratJalan::class, 's_jalan_id', 'id');
    }

}
