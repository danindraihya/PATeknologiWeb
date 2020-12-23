<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;
use PDF;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allBarang = Barang::all();

        return view('transaksi.index')->with('allBarang', $allBarang);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function tambahkanKeranjang(Request $request) {
        $barang = Barang::find($request->id);
        $cart = session()->get('cart');

        if(!$cart) {
            $cart = [
                $request->id => [
                    "id" => $barang->id,
                    "nama" => $barang->nama,
                    "harga" => $barang->harga,
                    "jumlah" => $request->jumlah
                ]
            ];
            session()->put('cart', $cart);
            return session()->get('cart');

        } elseif(isset($cart[$request->id])) {
            $cart[$request->id]['jumlah'] = $request->jumlah;
            session()->put('cart', $cart);
            return session()->get('cart');
        } else {
            $cart[$request->id] = [
                "id" => $barang->id,
                "nama" => $barang->nama,
                "harga" => $barang->harga,
                "jumlah" => $request->jumlah
            ];
            session()->put('cart', $cart);
            return session()->get('cart');
        }
    }

    public function hapusDariKeranjang(Request $request)
    {
        if($request->id) {

            $cart = session()->get('cart');

            if(isset($cart[$request->id])) {

                unset($cart[$request->id]);

                session()->put('cart', $cart);
            }
        }

        return session()->get('cart');
    }

    public function bayar(Request $request)
    {
        $transaksi = new Transaksi();
        $transaksi->cash = $request->cash;
        $transaksi->kembali = $request->kembali;
        $transaksi->total_pembayaran = $request->total_pembayaran;
        $transaksi->save();
        $carts = session()->get('cart');
        foreach($carts as $cart) {
            $transaksi->barang()->attach($cart['id'],
             ['total_harga' => $cart['harga'] * $cart['jumlah'],
              'jumlah' => $cart['jumlah']]);
        }
        session()->forget('cart');
        return $transaksi->id;
    }

    public function cetak(Request $request) {

        $detailTransaksi = DB::table('detail_transaksi')
                    ->where('transaksi_id', $request->input('transaksi_id'))
                    ->get();

        foreach($detailTransaksi as $barang) {
            $item = Barang::find($barang->barang_id);
            $barang->barang_id = $item->nama;
        }

        $pdf = PDF::loadView('transaksi.cetak', ['barang' => $detailTransaksi, 'total_harga' => $request->input('total_harga'), 'cash' => $request->input('cash'), 'kembali' => $request->kembali])->setPaper('a4', 'potrait');

        return $pdf->stream("cetak.pdf");
    }
}
