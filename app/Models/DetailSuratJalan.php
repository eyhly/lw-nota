<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailSuratJalan extends Model
{
    protected $table = 'detail_surat_jalan'; 
    public $timestamps = true; 

    protected $fillable = [
        'id',
        's_jalan_id',
        'nama_barang',
        'coly',
        'satuan_coly',
        'qty_isi',
        'nama_isi',
    ];

    public function suratjalan()
    {
        return $this->belongsTo(SuratJalan::class, 's_jalan_id', 'id');
    }
}
