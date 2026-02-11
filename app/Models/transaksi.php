<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class transaksi extends Model
{
    protected $primaryKey = 'id_transaksi';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function item_transaksi()
    {
        return $this->hasMany(ItemTransaksi::class, 'id_transaksi', 'id_transaksi');
    }
}
