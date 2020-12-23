<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allBarang = Barang::all();

        return view('barang/index')->with('allBarang', $allBarang);
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
        $this->validate($request, [
            'nama' => 'required',
            'kategori' => 'required',
            'harga' => 'required',
        ]);

        if($request->hasFile('gambar')){
            // $image = $request->file('gambar');
            $filenameWithExt = $request->file('gambar')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('gambar')->getClientOriginalExtension();
            $filenameToStore = $filename.'_'.time().'.'.$extension;
            // $img = Image::make($image->path());
            // $img->resize(750, 530, function ($constraint) {
            //     $constraint->aspectRatio();
            // })->save(public_path('/storage/gambar/'.$filenameToStore));

            $path = $request->file('gambar')->storeAs('public/gambar', $filenameToStore);
        } else {
            $filenameToStore = 'noimage.jpg';
        }

        $barang = new Barang;

        $barang->nama = $request->input('nama');
        $barang->kategori = $request->input('kategori');
        $barang->harga = $request->input('harga');
        $barang->gambar = $filenameToStore;
        $barang->save();

        return redirect('/barang')->with('success', 'Berhasil input menu');
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

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required',
            'kategori' => 'required',
            'harga' => 'required',
            'gambar' => 'image|nullable|max:2048'
        ]);

        if($request->hasFile('gambar')){
            // $image = $request->file('gambar');
            $filenameWithExt = $request->file('gambar')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('gambar')->getClientOriginalExtension();
            // $img = Image::make($image->path());
            $filenameToStore = $filename.'_'.time().'.'.$extension;
            // $img->resize(750, 530, function ($constraint) {
            //     $constraint->aspectRatio();
            // })->save('public/gambar/'.$filenameToStore);
            $path = $request->file('gambar')->storeAs('public/gambar', $filenameToStore);
        }

        $barang = Barang::find($request->input('id'));
        $barang->nama = $request->input('nama');
        $barang->kategori = $request->input('kategori');
        $barang->harga = $request->input('harga');
        if($request->hasFile('gambar')) {
            if($barang->gambar != 'noimage.jpg') {
                Storage::delete('public/gambar/'.$barang->gambar);
            }
            $barang->gambar = $filenameToStore;
        }
        $barang->save();

        return redirect('/barang')->with('success', 'Berhasil edit menu');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $barang = Barang::find($request->input('id'));

        if($barang->gambar != 'noimage.jpg') {
            Storage::delete('pulbic/gambar/'.$barang->gambar);
        }

        $barang->delete();

        return redirect('/barang')->with('success', 'Berhasil menghapus item');

    }
}
