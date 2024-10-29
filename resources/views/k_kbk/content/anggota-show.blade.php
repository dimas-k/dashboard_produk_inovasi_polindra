<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card p-2">
        <h5 class="card-header border-3 w-100 mb-2"><i class='bx bx-table me-2'></i>Data {{ $anggota->nama_produk }}</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-borderless">
                <tr>
                    <th>Nama Produk</th>
                    <td>: {{ $anggota->nama_produk }}</td>
                </tr>
                <tr>
                    <th>Deskripsi</th>
                    <td>: {{ $anggota->deskripsi }}</td>
                </tr>
                <tr>
                    <th>Nama Inventor</th>
                    <td>: {{ $anggota->inventor }}</td>
                </tr>
                <tr>
                    <th>anggota inventor</th>
                    <td>: {{ $anggota->anggota_inventor }}</td>
                </tr>
                <tr>
                    <th>Kelompok Keahlian</th>
                    <td>: {{ $anggota->kelompokKeahlian ? $anggota->kelompokKeahlian->nama_kbk : 'Tidak ada' }}</td>
                </tr>
                <tr>
                    <th>email</th>
                    <td>: {{ $anggota->email_inventor }}</td>
                </tr>
                <tr>
                    <th>Gambar Produk</th>
                    <td>: <img src="{{ asset( 'storage/' . $anggota->gambar) }}" alt="" style="max-width: 70%; height: auto"></td>
                </tr>
                <tr>
                    <th>Lampiran</th>
                    <td>: <a href={{ asset( 'storage/' . $anggota->lampiran) }} class=""
                        target="_blank">Lihat Lampiran</a></td>
                </tr>
            </table>
        </div>
    </div>
</div>