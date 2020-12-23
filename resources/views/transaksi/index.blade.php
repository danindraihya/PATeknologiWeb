@extends('layouts.base')
@section('content')
    <div class="input-group mb-3">
        <input type="disable" id="idBarang" disabled class="form-control" placeholder="ID Barang" aria-describedby="button-addon2">
        <button class="btn btn-outline-secondary" type="button" id="button-addon2" data-bs-toggle="modal" data-bs-target="#modalTambahBarang">Cari</button>
    </div>
    <div class="input-group mb-3">
        <input type="number" id="jumlahBarang" class="form-control" placeholder="Jumlah Barang" aria-describedby="button-addon2">
        <button class="btn btn-outline-secondary" id="btn-beli" type="button">Beli</button>
    </div>


    <div class="row">
        <div class="col">
            <table class="table">
                <thead>
                  <tr>
                    <th scope="col">ID Barang</th>
                    <th scope="col">Nama Barang</th>
                    <th scope="col">Jumlah Barang</th>
                    <th scope="col">Total Harga</th>
                    <th scope="col" colspan="2">Sunting</th>
                  </tr>
                </thead>
                <tbody id="keranjang">
                </tbody>
              </table>
        </div>
        <div class="col">
            <div class="form-group">
                <label for="totalPembayaran">Total Pembayaran : </label>
                <input type="number" class="form-control" disabled name="total_pembayaran" id="totalPembayaran">
            </div>
            <div class="form-group mt-3">
                <label for="cash">Cash : </label>
                <input type="number" class="form-control" name="cash" id="cash">
            </div>
            <div class="form-group mt-3">
                <label for="kembali">Kembali : </label>
                <input type="number" disabled class="form-control" name="kembali" id="kembali">
            </div>
            <button type="button" id="bayar" class="btn btn-primary mt-3">Bayar</button>
        </div>
    </div>

    <!-- Modal Tambah Barang -->
    <div class="modal fade" id="modalTambahBarang" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                      <tr>
                        <th scope="col">Nama Barang</th>
                        <th scope="col">Harga Barang</th>
                        <th scope="col">Opsi</th>
                      </tr>
                    </thead>
                    <tbody>
                        @if (count($allBarang) > 0)
                        @foreach ($allBarang as $barang)
                        <tr>
                            <th scope="row">{{$barang->nama}}</th>
                            <td>{{$barang->harga}}</td>
                            <td><button type="submit" class="btn btn-primary btn-pilih-barang" data-bs-dismiss="modal" data-id="<?= $barang->id; ?>">Pilih</button></td>
                        </tr>
                        @endforeach
                     @endif
                    </tbody>
                </table>

            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
        </div>
    </div>

    <form action="/cetak" method="post">
        @csrf
        <input type="number" hidden id="cetakTransaksi_id" name="transaksi_id">
        <input type="hidden" name="total_harga" id="cetakTotal_harga">
        <input type="hidden" id="cetakCash" name="cash">
        <input type="hidden" id="cetakKembali" name="kembali">
        <button type="submit" hidden id="btnCetak" onclick="return SubmitForm(this.form)">Cetak</button>
    </form>

    {{-- {!! Form::open(['url' => 'cetak', 'method' => 'POST']) !!}
        {{Form::hidden('transaksi_id', '', ['id' => 'cetakTransaksi_id'])}}
        {{Form::hidden('total_harga', '', ['id' => 'cetakTotal_harga'])}}
        {{Form::hidden('cash', '', ['id' => 'cetakCash'])}}
        {{Form::hidden('kembali', '', ['id' => 'cetakKembali'])}}
        {{Form::submit('Cetak', ['id' => 'btnCetak', 'onclick' => 'SubmitForm(this.form)', 'hidden'])}}
    {!! Form::close() !!} --}}

    <script>
        var tambahBarang = document.getElementsByClassName('btn-pilih-barang');

            for(let i = 0; i < tambahBarang.length; i++) {
                tambahBarang[i].addEventListener('click', () => {

                    document.getElementById('idBarang').value = tambahBarang[i].dataset.id;
                });
            }
    </script>

    <script>

        function hapusBarang() {
            var hapusBarang = document.getElementsByClassName('hapus-barang');
            for(let i = 0; i < hapusBarang.length; i++) {
                hapusBarang[i].addEventListener('click', () => {
                    axios.post('/transaksi/hapusBarang', {
                        id: hapusBarang[i].attributes['idBarang'].value
                    })
                    .then((res) => {
                        let markup = "";
                        let total_pembayaran = 0;
                        for(i in res.data){
                        markup+= "<tr> <th scope='row'>"+ res.data[i].id +"</th> <td>"+ res.data[i].nama +"</td> <td>"+ res.data[i].jumlah +"</td> <td>"+ res.data[i].jumlah*res.data[i].harga +"</td> <td><button idBarang='"+ res.data[i].id +"' class='btn btn-danger hapus-barang'>Hapus</button></td></tr>"
                        total_pembayaran += res.data[i].jumlah*res.data[i].harga;
                    }

                        var keranjang = document.getElementById('keranjang');
                        keranjang.innerHTML = markup;
                        var inputTotalPembayaran = document.getElementById('totalPembayaran');
                        inputTotalPembayaran.value = total_pembayaran;
                        this.hapusBarang();
                    });
                });
            }
        }

        var beliBarang = document.getElementById('btn-beli');

        beliBarang.addEventListener('click', () => {
            axios.post('/transaksi/tambahBarang', {
                id: document.getElementById('idBarang').value,
                jumlah: document.getElementById('jumlahBarang').value
            })
            .then((res) => {
                let markup = "";
                let total_pembayaran = 0;
                for(i in res.data){
                    markup+= "<tr> <th scope='row'>"+ res.data[i].id +"</th> <td>"+ res.data[i].nama +"</td> <td>"+ res.data[i].jumlah +"</td> <td>"+ res.data[i].jumlah*res.data[i].harga +"</td> <td><button idBarang='"+ res.data[i].id +"' class='btn btn-danger hapus-barang'>Hapus</button></td></tr>"
                    total_pembayaran += res.data[i].jumlah*res.data[i].harga;
                }

                var keranjang = document.getElementById('keranjang');
                keranjang.innerHTML = markup;
                var inputTotalPembayaran = document.getElementById('totalPembayaran');
                inputTotalPembayaran.value = total_pembayaran;
                hapusBarang();

            });
        });
    </script>

    <script>
        var bayar = document.getElementById('bayar');

        function SubmitForm(frm) {
            frm.submit();
        }

        bayar.addEventListener('click', () => {

            var keranjang = document.getElementById('keranjang');
            var inputTotalPembayaran = document.getElementById('totalPembayaran');
            var cash = document.getElementById('cash');
            var kembali = document.getElementById('kembali');

            if(cash.value < inputTotalPembayaran.value) {
                alert('Uang Pembayaran Kurang !');
            } else {
                axios.post('/transaksi/bayar', {
                cash: document.getElementById('cash').value,
                kembali: document.getElementById('cash').value - document.getElementById('totalPembayaran').value,
                total_pembayaran: document.getElementById('totalPembayaran').value
                })
                .then((res) => {


                    document.getElementById('cetakTransaksi_id').value = res.data;
                    document.getElementById('cetakTotal_harga').value = inputTotalPembayaran.value;
                    document.getElementById('cetakCash').value = cash.value;
                    document.getElementById('cetakKembali').value = cash.value - inputTotalPembayaran.value;

                    kembali.value = cash.value - inputTotalPembayaran.value;
                    cash.value = 0;
                    keranjang.innerHTML = "";
                    inputTotalPembayaran.value = 0;

                    if(confirm('Cetak invoice ?')) {
                        document.getElementById('btnCetak').click();
                    }
                });
            }

        });
    </script>
@endsection
