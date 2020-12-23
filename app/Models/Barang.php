<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaksi;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';

    public $primaryKey = 'id';

    public $timestamps = false;

    public function transaksi() {
        return $this->belongsToMany('App\Models\Transaksi', 'detail_transaksi', 'barang_id', 'transaksi_id')
                    ->as('detail_transaksi')
                    ->withPivot('jumlah', 'total_harga')
                    ->withTimestamps();
    }
}
