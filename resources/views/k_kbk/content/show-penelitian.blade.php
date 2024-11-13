<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card p-3">
        <h5 class="card-header border-3 w-100 mb-2"><i class='bx bx-table me-2'></i>Data {{ $penelitian->judul }}</h5>
        <img class="mx-auto d-block mb-5 mt-4" src="{{ asset( 'storage/' . $penelitian->gambar) }}" alt="" style="max-width: 50%; height: auto">
        <div class="table-responsive text-nowrap mt-4">
            <table class="table table-borderless">
                <tr>
                    <th>Judul</th>
                    <td>: {{ $penelitian->judul }}</td>
                </tr>
                <tr>
                    <th>Nama Penulis</th>
                    <td>: {{ $penelitian->penulis }}</td>
                </tr>

                <tr>
                    <th>email penulis</th>
                    <td>: {{ $penelitian->email_penulis }}</td>
                </tr>
                <tr>
                <tr>
                    <th>Penulis Korespondensi</th>
                    <td>: {{ $penelitian->penulis_korespondensi }} 
                        {{-- - {{$penelitian->penulis_korespondensi->jabatan}} --}}
                    </td>
                </tr>
                <tr>
                    <th>Anggota Penulis</th>
                    <td>
                        @if($penelitian->anggotaPenelitian)
                            @foreach($penelitian->anggotaPenelitian as $anggota)
                                <li>{{ $anggota->detailAnggota->nama_lengkap }} - {{ $anggota->detailAnggota->jabatan }}</li>
                            @endforeach
                        @else
                            <p>Tidak ada anggota penulis.</p>
                        @endif
                    </td>
                </tr>                
                <tr>
                    <th>Kelompok Keahlian</th>
                    <td>: {{ $penelitian->kelompokKeahlian ? $penelitian->kelompokKeahlian->nama_kbk : 'Tidak ada' }}</td>
                </tr>
                <tr>
                    <th>Abstrak</th>
                    {{-- <td>: <a href={{ asset('storage/' . $penelitian->abstrak) }} class=""
                        target="_blank">Lihat Abstrak</a></td> --}}
                    <td style="white-space: normal; word-wrap: break-word;">: {{ $penelitian->abstrak }}</td>
                </tr>
                <tr>
                    <th>Lampiran</th>
                    <td>: <a href={{ asset('storage/'.$penelitian->lampiran) }} class=""
                        target="_blank">Lihat Lampiran</a></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>:
                        {{ $penelitian->status }}
                    </td>
                </tr>
                {{-- <tr>
                    <th>Gambar Penelitian</th>
                    <td>: <img src="{{ asset('storage/'.$penelitian->gambar) }}" alt="" style="max-width: 70%; height: auto"></td>
                </tr> --}}
                <tr>
                    <th>Tanggal Publikasi</th>
                    <td>:
                        {{ \Carbon\Carbon::parse($penelitian->tanggal_publikasi)->format('d-m-Y') }}
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>