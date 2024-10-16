<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card p-2">
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show rounded" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <h5 class="card-header"><i class='bx bx-table me-2'></i>Tabel Ketua Kelompok Bidang Keahlian</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr class="text-nowrap">
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Nama Inventor</th>
                        <th>No Handphone Inventor</th>
                        <th>Aksi</th>
                        <th>Validasi</th>

                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($produk as $i => $p)
                        <tr>
                            <th scope="row">{{ ($produk->currentPage() - 1) * $produk->perPage() + $loop->iteration }}
                            </th>
                            <td>{{ $p->nama_lengkap }}</td>
                            <td>{{ $p->nip }}</td>
                            <td>{{ $p->kbk ? $p->kbk->nama_kbk : 'Tidak ada' }}</td>
                            <td>
                                <a href="{{ route('show.k-kbk', $p->id) }}" class="btn btn-sm btn-success"><i
                                        class='bx bxs-show'></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-end mt-2">
                {{ $produk->links() }}
            </div>
        </div>
    </div>
</div>
