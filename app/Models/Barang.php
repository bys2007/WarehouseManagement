<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $primaryKey = 'id_barang';
    protected $guarded = [];

    public function item_transaksi()
    {
        return $this->hasMany(ItemTransaksi::class, 'id_barang', 'id_barang');
    }
}
