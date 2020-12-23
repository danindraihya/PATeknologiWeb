<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Barang;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';

    public $primaryKey = 'id';

    public $timestamps = true;

    public function barang(){
        return $this->belongsToMany('App\Models\Barang', 'detail_transaksi', 'transaksi_id', 'barang_id')
                    ->as('detail_transaksi')
                    ->withPivot('jumlah', 'total_harga')
                    ->withTimestamps();
    }
}
