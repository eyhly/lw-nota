<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailNota extends Model
{
    protected $table = 'detail_nota'; 
    public $timestamps = true; 

    protected $fillable = [
        'id',
        'nota_id',
        'nama_barang',
        'coly',
        'satuan_coly',
        'qty_isi',
        'nama_isi',
        'jumlah',
        'harga',
        'diskon',
        'total',
    ];

    public function nota()
    {
        return $this->belongsTo(Nota::class, 'nota_id', 'id');
    }
}
