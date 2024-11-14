<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-end mb-4">
        <div class="card p-2">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#basicModal">
                <i class='bx bx-plus-circle me-2'></i>Tambah Penelitian
            </button>
            <div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel1">Tambah Penelitian</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="/k-kbk/penelitian/store" enctype="multipart/form-data" method="post"
                                id="uploadForm">
                                @csrf
                                <div class="row">
                                    <div class="col mb-6">
                                        <label for="" id="kbk" class="form-label">
                                            KBK</label>

                                        <input type="hidden" name="kbk_id" value="{{ $kkbk->id }}">
                                        <input class="form-control" type="text"
                                        id="nama_kbk" value="{{ $kkbk->nama_kbk}}" readonly/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-6">
                                        <label for="nameBasic" class="form-label">Judul</label>
                                        <input type="text" id="judul" class="form-control"
                                            placeholder="Masukkan Judul" name="judul" />
                                        @error('judul')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-6">
                                        <label for="nameBasic" class="form-label">Abstrak <small
                                                class="text-danger">(abstrak tidak lebih dari 260 kata)</small></label>
                                        <textarea id="limitedTextarea" placeholder="Masukkan abstrak penelitian" class="form-control" style="height: 150px"
                                            name="abstrak"></textarea>
                                        <p id="wordCount">0/260 kata</p>
                                        {{-- <input type="file" id="abstrak" class="form-control" name="abstrak" /> --}}
                                        @error('abstrak')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-6">
                                        <label for="nameBasic" class="form-label">Gambar Penelitian</label>
                                        <input type="file" id="gambar" class="form-control" name="gambar" />
                                        @error('gambar')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-6">
                                        <label for="nameBasic" class="form-label">Nama Penulis</label>
                                        <input type="text" id="penulis" class="form-control"
                                            placeholder="Masukkan nama penulis" name="penulis" />
                                        @error('penulis')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col mb-6">
                                        <label for="nameBasic" class="form-label">Email Penulis</label>
                                        <input type="email" id="email" class="form-control"
                                            placeholder="Masukkan email penulis" name="email_penulis" />
                                        @error('email_penulis')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-6">
                                        <label for="penulis_korespondensi">Pilih Penulis Korespondensi</label><br>
                                        <select class="selectpicker w-100" name="penulis_korespondensi" data-live-search="true" id="penulis_korespondensi" title="Pilih salah satu">
                                            @foreach ($anggotaKelompok as $anggota)
                                            <option value="{{ $anggota->id }}">{{ $anggota->nama_lengkap }} - {{ $anggota->jabatan }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col mb-6">
                                        <label for="anggota_penulis">Pilih Anggota Penulis</label>
                                        <select class="selectpicker w-100" data-live-search="true" id="anggota_penulis"
                                            name="anggota_penulis[]" multiple title="Pilih Anggota Penulis..">
                                            @foreach ($anggotaKelompok as $anggota)
                                                <option value="{{ $anggota->id }}">{{ $anggota->nama_lengkap }} - {{ $anggota->jabatan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-6">
                                        <label for="nameBasic" class="form-label">Lampiran</label>
                                        <input type="file" id="lampiran" class="form-control" name="lampiran" />
                                        @error('lampiran')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-6">
                                        <label for="tanggal_publikasi" class="form-label">Tanggal Publikasi</label>
                                        <input type="date" name="tanggal_publikasi" id="tanggal_publikasi"
                                            class="form-control @error('tanggal_publikasi') is-invalid @enderror">
                                        @error('tanggal_publikasi')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <hr class="border-3 w-100">
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary me-1"
                                        data-bs-dismiss="modal">
                                        Batal
                                    </button>
                                    <button type="submit" class="btn btn-primary ms-1"
                                        id="btnSubmit">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card p-2">
        <h5 class="card-header"><i class='bx bx-table me-2'></i>Tabel Penelitian</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr class="text-nowrap">
                        <th>No</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>email Penulis</th>
                        <th>Tanggal Publikasi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($penelitians as $i => $p)
                        <tr>
                            <th scope="row">
                                {{ ($penelitians->currentPage() - 1) * $penelitians->perPage() + $loop->iteration }}
                            </th>
                            <td>{{ $p->judul }}</td>
                            <td>{{ $p->penulis }}</td>
                            <td>{{ $p->email_penulis }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal_publikasi)->format('d-m-Y') }}</td>
                            <td>
                                @if ($p->status === 'Tervalidasi')
                                    <span class="badge bg-label-success me-1">{{ $p->status }}</span>
                                @else
                                    <span class="badge bg-label-warning me-1">{{ $p->status }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('show.penelitian', $p->id) }}" class="btn btn-sm btn-primary"><i
                                        class='bx bxs-show'></i></a>
                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#basicModal{{ $p->id }}">
                                    <i class='bx bx-pencil'></i>
                                </button>
                                <div class="modal fade" id="basicModal{{ $p->id }}" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel1">Update Penelitian <br>
                                                    {{ $p->judul }}
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('edit.penelitian', $p->id) }}"
                                                    enctype="multipart/form-data" method="post" id="editForm_{{ $p->id }}">
                                                    @method('PUT')
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col mb-6">
                                                            <label for="exampleFormControlSelect1" id="kbk"
                                                                class="form-label">KBK</label>
                                                            <input class="form-control" type="text"
                                                                id="kelompokKeahlianName"
                                                                value="{{ $p->kelompokKeahlian ? $p->kelompokKeahlian->nama_kbk : 'Tidak ada' }}"
                                                                readonly />
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col mb-6">
                                                            <label for="nameBasic" class="form-label">Judul</label>
                                                            <input type="text" id="judul" class="form-control"
                                                                value="{{ $p->judul }}" name="judul" />
                                                            @error('judul')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col mb-6">
                                                            <label for="nameBasic" class="form-label">Abstrak <small
                                                                class="text-danger">(abstrak tidak lebih dari 260 kata)</small></label>
                                                            <textarea id="limitedTextarea{{ $p->id }}" placeholder="Masukkan abstrak penelitian" class="form-control" style="height: 150px"
                                                                name="abstrak">{{ $p->abstrak }}</textarea>
                                                            <p id="wordCount{{ $p->id }}">0/260 kata</p>
                                                            @error('abstrak')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                            {{-- <span class="text-danger"><small><i
                                                                        class='bx bxs-error me-1'></i>Jika tidak ada
                                                                    perubahan file tidak usah dinputkan
                                                                    kembali</small></span> --}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col mb-6">
                                                            <label for="nameBasic" class="form-label">Gambar
                                                                Penelitian</label>
                                                            <input type="file" id="gambar" class="form-control"
                                                                name="gambar" />
                                                            @error('gambar')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                            <span class="text-danger"><small><i
                                                                        class='bx bxs-error me-1'></i>Jika tidak ada
                                                                    perubahan file tidak usah dinputkan
                                                                    kembali</small></span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col mb-6">
                                                            <label for="nameBasic" class="form-label">Nama
                                                                Penulis</label>
                                                            <input type="text" id="penulis" class="form-control"
                                                                value="{{ $p->penulis }}" name="penulis" />
                                                            @error('penulis')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col mb-6">
                                                            <label for="nameBasic" class="form-label">Email
                                                                Penulis</label>
                                                            <input type="email" id="email" class="form-control"
                                                                value="{{ $p->email_penulis }}"
                                                                name="email_penulis" />
                                                            @error('email_penulis')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col mb-6">
                                                            <label for="nameBasic" class="form-label">Pilih Penulis
                                                                Korespondensi</label>
                                                                <li>{{ $p->penulisKorespondensi->nama_lengkap }} - {{ $p->penulisKorespondensi->jabatan }} </li>
                                                                <br>
                                                                <select class="selectpicker w-100" data-live-search="true"
                                                                    id="penulis_korespondensi" name="penulis_korespondensi"
                                                                    title="Pilih salah satu..">
                                                                    @foreach ($penelitianAnggota as $anggota)
                                                                        <option value="{{ $anggota->id }}">
                                                                            {{ $anggota->nama_lengkap }} - {{ $anggota->jabatan }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <br>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col mb-6">
                                                            <label for="nameBasic" class="form-label">Pilih Anggota
                                                                Penulis</label>
                                                            @foreach ($p->anggotaPenelitian as $anggota)
                                                                <li>{{ $anggota->detailAnggota->nama_lengkap }} - {{ $anggota->detailAnggota->jabatan }}</li>
                                                            @endforeach
                                                            <br>
                                                            <select class="selectpicker w-100" data-live-search="true"
                                                                id="anggota_penulis" name="anggota_penulis[]" multiple
                                                                title="Pilih Anggota Penulis..">
                                                                @foreach ($penelitianAnggota as $anggota)
                                                                    <option value="{{ $anggota->id }}"
                                                                        @if ($p->anggotaPenelitian && $p->anggotaPenelitian->pluck('id')->contains($anggota->id)) selected @endif>
                                                                        {{ $anggota->nama_lengkap }} - 
                                                                        {{-- {{ $anggota->detailAnggota->jabatan }} --}}
                                                                    </option>
                                                                @endforeach
                                                            </select><br>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col mb-6">
                                                            <label for="nameBasic" class="form-label">Lampiran</label>
                                                            <input type="file" id="lampiran" class="form-control"
                                                                name="lampiran" />
                                                            @error('lampiran')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                            <span class="text-danger"><small><i
                                                                        class='bx bxs-error me-1'></i>Jika tidak ada
                                                                    perubahan file tidak usah dinputkan
                                                                    kembali</small></span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col mb-6">
                                                            <label for="tanggal_publikasi" class="form-label">Tanggal
                                                                Publikasi</label>
                                                            <input type="date" name="tanggal_publikasi"
                                                                id="tanggal_publikasi"
                                                                value="{{ $p->tanggal_publikasi ? \Carbon\Carbon::parse($p->tanggal_publikasi)->format('Y-m-d') : '' }}"
                                                                class="form-control @error('tanggal_publikasi') is-invalid @enderror">
                                                            @error('tanggal_publikasi')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <hr class="border-3 w-100">
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-secondary me-1"
                                                            data-bs-dismiss="modal">
                                                            Batal
                                                        </button>
                                                        <button type="submit" class="btn btn-primary ms-1"
                                                            id="btnSubmit">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <form style="margin-left: 15px" method="post"
                                    action="{{ route('hapus.penelitian', $p->id) }}" id="deleteForm">
                                    @method('DELETE')
                                    @csrf
                                    <button class="btn btn-sm btn-danger" type="submit"
                                        onclick="deleteConfirm(event)"><i class='bx bx-trash'></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                <img src="{{ asset('img/no-data.jpg') }}" style="width:15%; height:auto">
                                <p>Opss.. <br> <span class="fw-bold">Tidak ada data yang tersedia.</span></p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-end mt-2">
                {{ $penelitians->links() }}
            </div>
        </div>
    </div>
</div>
