<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card p-2">
        <h5 class="card-header border-3 w-100 mb-2"><i class='bx bx-table me-2'></i>Data {{ $penelitian->nama_produk }}</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-borderless">

                <tr>
                    <th>Judul Penelitian</th>
                    <td>: {{ $penelitian->judul }}</td>
                </tr>
                <tr>
                    <th>Penulis</th>
                    <td>: {{ $penelitian->penulis }}</td>
                </tr>
                <tr>
                    <th>Anggota Inventor</th>
                    <td>: {{ $penelitian->anggota_inventor }}</td>
                </tr>
                <tr>
                    <th>status produk</th>
                    <td>: {{ $penelitian->status }}</td>
                </tr>
                <tr>
                    <th>Abstrak</th>
                    <td>: <a href={{ asset('storage/public/' . $penelitian->abstrak) }} class="" target="_blank">Lihat
                            Abstrak</a></td>
                </tr>
                <tr>
                    <th>Lampiran</th>
                    <td>: <a href={{ asset('storage/public/' . $penelitian->lampiran) }} class="" target="_blank">Lihat
                            Lampiran</a></td>
                </tr>
                <tr>
                    <th>Gambar</th>
                    <td>: <img src="{{ asset('storage/public/' . $penelitian->gambar) }}" alt="" style="width: 220px; height: 130px"></td>
                </tr>
            </table>
        </div>
    </div>
</div>
