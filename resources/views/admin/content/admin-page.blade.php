<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-end mb-4">
        <div class="card p-2">
            {{-- <a href="" class="btn btn-success"><i class='bx bx-plus-circle me-2'></i>Tambah KBK</a> --}}
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#basicModal">
                <i class='bx bx-plus-circle me-2'></i>Tambah Admin
            </button>

            <!-- Modal -->
            <div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel1">Tambah Admin</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="/admin/kelompok-bidang-keahlian/create" method="post" id="uploadForm">
                                @csrf
                                <div class="row">
                                    <div class="col mb-6">
                                        <label for="nameBasic" class="form-label">Nama Lengkap</label>
                                        <input type="text" id="namaKbk" class="form-control"
                                            placeholder="Masukkan nama lengkap" name="nama_kbk" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-6">
                                        <label for="nameBasic" class="form-label">Jurusan</label>
                                        <input type="text" id="jurusan" class="form-control"
                                            placeholder="Masukkan jurusan" name="jurusan" />
                                    </div>
                                </div>


                                <br>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary me-1"
                                        data-bs-dismiss="modal">
                                        Batal
                                    </button>
                                    <button type="submit" class="btn btn-primary ms-1" id="btnSubmit">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card p-2">
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show rounded" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <h5 class="card-header"><i class='bx bx-table me-2'></i>Tabel Admin</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr class="text-nowrap">
                        <th>No</th>
                        <th>Nama Lengkap</th>
                        <th>NIP</th>
                        <th>Jabatan</th>
                        <th>No Handphone</th>
                        <th>Email</th>
                        <th>Aksi</th>

                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($admin as $i =>$adm)
                        <tr>
                            <th scope="row">{{ $i + 1 }}</th>
                            <td>{{ $adm->nama_lengkap }}</td>
                            <td>{{ $adm->nip }}</td>
                            <td>{{ $adm->jabatan }}</td>
                            <td>{{ $adm->no_hp }}</td>
                            <td>{{ $adm->email }}</td>
                            <td>
                                <a href="" class="btn btn-sm btn-success"><i class='bx bxs-show'></i></a>
                                <a href="" class="btn btn-sm btn-primary"><i class='bx bx-pencil'></i></a>
                                <a href="" class="btn btn-sm btn-danger"><i class='bx bx-trash'></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
