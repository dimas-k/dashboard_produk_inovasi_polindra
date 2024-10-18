<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card p-2">
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show rounded" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <h5 class="card-header"><i class='bx bx-table me-2'></i>Tabel Produk KBK {{ $kbk_navigasi1->nama_kbk }}</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr class="text-nowrap">
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Nama Inventor</th>
                        <th>Email Inventor</th>
                        <th>STATUS</th>
                        <th>Aksi</th>
                        <th>Validasi</th>

                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($data_produk as $i => $p)
                        <tr>
                            <th scope="row">{{ ($data_produk->currentPage() - 1) * $data_produk->perPage() + $loop->iteration }}
                            </th>
                            <td>{{ $p->nama_produk }}</td>
                            <td>{{ $p->inventor }}</td>
                            <td>{{ $p->email_inventor }}</td>
                            <td>{{ $p->status}}</td>
                            <td>
                                <a href="{{ route('show.produk', $p->id_produks) }}" class="btn btn-sm btn-success"><i
                                    class='bx bxs-show'></i></a>
                            </td>
                            
                            {{-- <td>
                                <a href="{{ route('show.k-kbk', $p->id) }}" class="btn btn-sm btn-success"><i
                                        class='bx bxs-show'></i></a>
                            </td> --}}
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-end mt-2">
                {{ $data_produk->links() }}
            </div>
        </div>
    </div>
</div>
