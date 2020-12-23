@extends('layouts.base')
@section('content')

<div class="container mt-3">

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahBarangModal">
        Tambah Barang
    </button>

    <!-- Modal Tambah Barang -->
    <div class="modal fade" id="tambahBarangModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {!! Form::open(['barang' => 'BarangController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                    <div class="form-group">
                        {{Form::label('nama', 'Nama :')}}
                        {{Form::text('nama', '', ['class' => 'form-control'])}}
                    </div>
                    <div class="form-group mt-2">
                        {{Form::label('kategori', 'Kategori :')}}
                        {{Form::select('kategori', ['minuman' => 'Minuman', 'makanan' => 'Makanan', 'snack' => 'Snack'], 'minuman', ['class' => 'form-control'])}}
                    </div>
                    <div class="form-group mt-2">
                        {{Form::label('harga', 'Harga :')}}
                        {{Form::text('harga', '', ['class' => 'form-control'])}}
                    </div>
                    <div class="form-group mt-2">
                        {{Form::file('gambar')}}
                    </div>

            </div>
            <div class="modal-footer">
                    {{Form::submit('Buat', ['class' => 'btn btn-primary mt-2'])}}
                {!! Form::close() !!}
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
        </div>
    </div>

    <!-- Modal Edit Barang-->
    <div class="modal fade" id="editBarangModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {!! Form::open(['barang' => 'BarangController@update', 'method' => 'PUT', 'enctype' => 'multipart/form-data']) !!}
                    {{Form::hidden('id', '', ['id' => 'idBarangEdit'])}}
                    <div class="form-group">
                        {{Form::label('nama', 'Nama :')}}
                        {{Form::text('nama', '', ['class' => 'form-control', 'id' => 'namaBarang'])}}
                    </div>
                    <div class="form-group mt-2">
                        {{Form::label('kategori', 'Kategori :')}}
                        {{Form::select('kategori', ['minuman' => 'Minuman', 'makanan' => 'Makanan', 'snack' => 'Snack'], 'minuman', ['class' => 'form-control', 'id' => 'kategoriBarang'])}}
                    </div>
                    <div class="form-group mt-2">
                        {{Form::label('harga', 'Harga :')}}
                        {{Form::text('harga', '', ['class' => 'form-control', 'id' => 'hargaBarang'])}}
                    </div>
                    <div class="form-group mt-2">
                        {{Form::file('gambar')}}
                    </div>
            </div>
            <div class="modal-footer">
                    {{Form::submit('Edit', ['class' => 'btn btn-warning mt-2'])}}
                {!! Form::close() !!}
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
        </div>
    </div>

    <!-- Modal Delete Barang-->
    <div class="modal fade" id="deleteBarangModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            Yakin mengahpus barang?
            </div>
            <div class="modal-footer">
                {!! Form::open(['barang' => 'BarangController@destroy', 'method' => 'DELETE']) !!}
                        {{Form::hidden('id', '', ['id' => 'idBarangDelete'])}}
                        {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
                {!! Form::close() !!}
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
        </div>
    </div>

    <div id="barang" class="row mt-3">
        @if (count($allBarang) > 0)
            @foreach ($allBarang as $barang)
                <div class="col">
                    <div class="card" style="width: 18rem;">
                    <img src="/storage/gambar/{{$barang->gambar}}" class="card-img-top" alt="...">
                        <div class="card-body">
                          <h5 class="card-title">{{$barang->nama}}</h5>
                        <p class="card-text">Jenis: {{$barang->kategori}} <br>Harga: {{$barang->harga}}</p>
                          <a href="#" class="btn-editBarang btn btn-warning" data-id="<?= $barang->id; ?>" data-nama="<?= $barang->nama; ?>" data-kategori="<?= $barang->kategori; ?>" data-harga="<?= $barang->harga; ?>" >Edit</a>
                          <a href="#" class="btn-hapusBarang btn btn-danger" data-id="<?= $barang->id; ?>">Hapus</a>
                        </div>
                      </div>
                </div>
            @endforeach
        @endif
    </div>

</div>
</body>

<script>
var editBarang = document.getElementsByClassName('btn-editBarang');

for(let i = 0; i < editBarang.length; i++){
    editBarang[i].addEventListener('click', () => {
        var modal = new bootstrap.Modal(document.getElementById('editBarangModal'));
        console.log(editBarang[i].dataset.id);
        document.getElementById('idBarangEdit').value = editBarang[i].dataset.id;
        document.getElementById('namaBarang').value = editBarang[i].dataset.nama;
        document.getElementById('kategoriBarang').value = editBarang[i].dataset.kategori;
        document.getElementById('hargaBarang').value = editBarang[i].dataset.harga;

        modal.show();
    });
}

</script>

<script>
var deleteBarang = document.querySelectorAll('div#barang .btn-hapusBarang');

for(let i = 0; i < deleteBarang.length; i++) {
        deleteBarang[i].addEventListener('click', () => {
        var modal = new bootstrap.Modal(document.getElementById('deleteBarangModal'));
        console.log(deleteBarang[i].dataset.id);
        document.getElementById('idBarangDelete').value = deleteBarang[i].dataset.id;
        console.log(document.getElementById('idBarangDelete').value);

        modal.show();
    });
}

</script>
@endsection
