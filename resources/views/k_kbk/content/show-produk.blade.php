<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card p-2">
        <h5 class="card-header border-3 w-100 mb-2"><i class='bx bx-table me-2'></i>Data {{ $produk->nama_produk }}</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-borderless">
                <tr>
                    <th>Nama Produk</th>
                    <td>: {{ $produk->nama_produk }}</td>
                </tr>
                <tr>
                    <th>Deskripsi</th>
                    <td>: {{ $produk->deskripsi }}</td>
                </tr>
                <tr>
                    <th>Gambar Produk</th>
                    <td>: <a href={{ asset( $produk->gambar) }} class=""
                        target="_blank">Lihat Gambar</a></td>
                </tr>
                <tr>
                    <th>Nama Inventor</th>
                    <td>: {{ $produk->inventor }}</td>
                </tr>
                <tr>
                    <th>anggota inventor</th>
                    <td>: {{ $produk->anggota_inventor }}</td>
                </tr>
                <tr>
                    <th>Kelompok Keahlian</th>
                    <td>: {{ $produk->kelompokKeahlian ? $produk->kelompokKeahlian->nama_kbk : 'Tidak ada' }}</td>
                </tr>
                <tr>
                    <th>email</th>
                    <td>: {{ $produk->email_inventor }}</td>
                </tr>
                <tr>
                    <th>Lampiran</th>
                    <td>: <a href={{ asset( $produk->lampiran) }} class=""
                        target="_blank">Lihat Lampiran</a></td>
                </tr>
            </table>
        </div>
    </div>
</div>