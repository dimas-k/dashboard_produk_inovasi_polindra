<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card p-3">
        <h5 class="card-header border-3 w-100 mb-2"><i class='bx bx-table me-2'></i>Data {{ $penelitian->judul }}</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-borderless">
                <tr>
                    <th>Judul</th>
                    <td>: {{ $penelitian->judul }}</td>
                </tr>
                <tr>
                    <th>Nama Penulis</th>
                    <td>: {{ $penelitian->penulis }}</td>
                </tr>
                <div class="row">
                    <div class="col mb-6">
                        <label for="anggota_inventor">Anggota Penulis</label>
                        <select class="selectpicker w-100" data-live-search="true" id="anggota_inventor" name="anggota_inventor[]" multiple title="Pilih Anggota Inventor.." >
                            @foreach($anggotaKelompok as $anggota)
                                <option value="{{ $anggota->id }}">{{ $anggota->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <tr>
                    <th>email</th>
                    <td>: {{ $penelitian->email_penulis }}</td>
                </tr>
                <tr>
                    <th>Kelompok Keahlian</th>
                    <td>: {{ $penelitian->kelompokKeahlian ? $penelitian->kelompokKeahlian->nama_kbk : 'Tidak ada' }}</td>
                </tr>
                <tr>
                    <th>Abstrak</th>
                    <td>: <a href={{ asset('storage/' . $penelitian->abstrak) }} class=""
                        target="_blank">Lihat Abstrak</a></td>
                </tr>
                <tr>
                    <th>Lampiran</th>
                    <td>: <a href={{ asset('storage/'.$penelitian->lampiran) }} class=""
                        target="_blank">Lihat Lampiran</a></td>
                </tr>
                <tr>
                    <th>Gambar Penelitian</th>
                    <td>: <img src="{{ asset('storage/'.$penelitian->gambar) }}" alt="" style="max-width: 70%; height: auto"></td>
                </tr>
                <tr>
                    <th>Tanggal Publikasi</th>
                    <td>:
                        {{ $penelitian->tanggal_publikasi }}
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>