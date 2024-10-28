<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-end mb-4">
        <div class="card p-2">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#basicModal">
                <i class='bx bx-plus-circle me-2'></i>Tambah Produk Inovasi
            </button>
            <div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel1">Tambah Produk Inovasi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="/k-kbk/produk/store" enctype="multipart/form-data" method="post"
                                id="uploadForm">
                                @csrf
                                <div class="row">
                                    <div class="col mb-6">
                                        <label for="exampleFormControlSelect1" id="kbk" class="form-label">Pilih
                                            KBK</label>
                                        <select class="form-select" id="exampleFormControlSelect1" name="kbk_id"
                                            aria-label="Default select example">
                                            <option value="" selected>Pilih KBK</option>
                                            @foreach ($kkbk as $j_kbk)
                                                <option value="{{ $j_kbk->id }}">{{ $j_kbk->nama_kbk }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-6">
                                        <label for="nameBasic" class="form-label">Nama produk</label>
                                        <input type="text" id="nama_produk" class="form-control"
                                            placeholder="Masukkan nama produk" name="nama_produk" />
                                        @error('nama_produk')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-6">
                                        <label for="nameBasic" class="form-label">Deskripsi</label>
                                        <textarea class="form-control" name="deskripsi" placeholder="Masukkan deskripsi" id="floatingTextarea"
                                            style="height: 100px"></textarea>
                                        @error('deskripsi')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-6">
                                        <label for="nameBasic" class="form-label">Gambar Produk</label>
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
                                        <label for="nameBasic" class="form-label">Nama Inventor</label>
                                        <input type="text" id="inventor" class="form-control"
                                            placeholder="Masukkan nama inventor" name="inventor" />
                                        @error('inventor')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-6">
                                        <label for="nameBasic" class="form-label">Anggota Inventor</label>
                                        <textarea class="form-control" placeholder="Masukkan anggota inventor" id="floatingTextarea" style="height: 80px"
                                            name="anggota_inventor"></textarea>
                                        @error('anggota_inventor')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-6">
                                        <label for="nameBasic" class="form-label">Email Inventor</label>
                                        <input type="email" id="email" class="form-control"
                                            placeholder="Masukkan email inventor" name="email_inventor" />
                                        @error('email_inventor')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
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
        <h5 class="card-header"><i class='bx bx-table me-2'></i>Tabel Produk Inovasi</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr class="text-nowrap">
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Nama Inventor</th>
                        <th>email inventor</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($produks as $i => $p)
                        <tr>
                            <th scope="row">
                                {{ ($produks->currentPage() - 1) * $produks->perPage() + $loop->iteration }}
                            </th>
                            <td>{{ $p->nama_produk }}</td>
                            <td>{{ $p->inventor }}</td>
                            <td>{{ $p->email_inventor }}</td>
                            <td>
                                @if ($p->status === 'Tervalidasi')
                                    <span class="badge bg-label-success me-1">{{ $p->status }}</span>
                                @else
                                    <span class="badge bg-label-warning me-1">{{ $p->status }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('lihat.produk', $p->id) }}" class="btn btn-sm btn-primary"><i
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
                                                <h5 class="modal-title" id="exampleModalLabel1">Update Produk
                                                    {{ $p->nama_produk }}
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('update.produk', $p->id) }}"
                                                    enctype="multipart/form-data" method="post"
                                                    id="editForm_{{ $p->id }}">
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
                                                            <label for="nameBasic" class="form-label">Nama
                                                                produk</label>
                                                            <input type="text"
                                                                id="nama_produk_{{ $p->id }}"
                                                                class="form-control" value="{{ $p->nama_produk }}"
                                                                name="nama_produk" />
                                                            @error('nama_produk')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col mb-6">
                                                            <label for="nameBasic"
                                                                class="form-label">Deskripsi</label>
                                                            <textarea class="form-control" name="deskripsi" id="deskripsi_{{ $p->id }}" style="height: 100px">{{ $p->deskripsi }}</textarea>
                                                            @error('deskripsi')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col mb-6">
                                                            <label for="nameBasic" class="form-label">Gambar
                                                                Produk</label>
                                                            <input type="file" id="gambar_{{ $p->id }}"
                                                                class="form-control" name="gambar" />
                                                            @error('gambar')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                            <span class="text-danger"><small><i
                                                                class='bx bxs-error me-1'></i>Jika tidak ada perubahan file tidak usah dinputkan kembali</small></span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col mb-6">
                                                            <label for="nameBasic" class="form-label">Nama
                                                                Inventor</label>
                                                            <input type="text" id="inventor_{{ $p->id }}"
                                                                class="form-control" value="{{ $p->inventor }}"
                                                                name="inventor" />
                                                            @error('inventor')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col mb-6">
                                                            <label for="nameBasic" class="form-label">Anggota
                                                                Inventor</label>
                                                            <textarea class="form-control" id="anggota_{{ $p->id }}" style="height: 80px" name="anggota_inventor">{{ $p->anggota_inventor }}</textarea>
                                                            @error('anggota_inventor')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col mb-6">
                                                            <label for="nameBasic" class="form-label">Email
                                                                Inventor</label>
                                                            <input type="email" id="email_{{ $p->id }}"
                                                                class="form-control" value="{{ $p->email_inventor }}"
                                                                name="email_inventor" />
                                                            @error('email_inventor')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror

                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col mb-6">
                                                            <label for="nameBasic" class="form-label">Lampiran</label>
                                                            <input type="file" id="lampiran_{{ $p->id }}"
                                                                class="form-control" name="lampiran" />
                                                            @error('lampiran')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                            <span class="text-danger"><small><i
                                                                class='bx bxs-error me-1'></i>Jika tidak ada perubahan file tidak usah dinputkan kembali</small></span>
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
                                {{-- <form style="margin-left: 15px" method="post"
                                    action="{{ route('hapus.produk', $p->id) }}" id="deleteForm">
                                    @method('DELETE')
                                    @csrf
                                    <button class="btn btn-sm btn-danger" type="submit"
                                        onclick="deleteConfirm(event)"><i class='bx bx-trash'></i></button>
                                </form> --}}
                                <div class="btn btn-sm btn-danger">
                                    <form method="post" action="{{ route('hapus.produk', $p->id) }}"
                                        id="deleteForm">
                                        @method('DELETE')
                                        @csrf
                                        <a type="submit" onclick="deleteConfirm(event)"><i
                                                class='bx bx-trash'></i></a>
                                    </form>
                                </div>
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
                {{ $produks->links() }}
            </div>
        </div>
    </div>
</div>
