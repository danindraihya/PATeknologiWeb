<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Barang;

class PenjualanController extends Controller
{
    public function index() {

        $makanan = 0;
        $minuman = 0;
        $snack = 0;
        $date = Carbon::today()->toDateString();
        $rekap = DB::table('detail_transaksi')
                    ->whereDate('created_at', $date)
                    ->get();
        foreach($rekap as $item) {
            $barang = Barang::find($item->barang_id);
            if($barang->kategori == 'makanan') {
                $makanan += $item->jumlah;
            } elseif($barang->kategori == 'minuman') {
                $minuman += $item->jumlah;
            } else {
                $snack += $item->jumlah;
            }
        }
        $data = array(
            'tanggal' => date("F jS, Y", strtotime("now")),
            'makanan' => $makanan,
            'minuman' => $minuman,
            'snack' => $snack
        );
        return view('penjualan.index')->with('data', $data);
    }
}
