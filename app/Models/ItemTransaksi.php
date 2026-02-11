<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemTransaksi extends Model
{
    protected $primaryKey = 'id_item_transaksi';
    protected $guarded = [];

    public function transaksi()
    {
        return $this->belongsTo(transaksi::class, 'id_transaksi', 'id_transaksi');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }
}
