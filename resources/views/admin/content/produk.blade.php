<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card p-2">
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
                    @php
                        $nomor = ($data_produk->currentPage() - 1) * $data_produk->perPage() + 1; // Menghitung nomor awal berdasarkan halaman
                    @endphp

                    @foreach ($data_produk as $p)
                        <tr>
                            <th scope="row">{{ $nomor++ }}</th>
                            <td>{{ $p->nama_produk }}</td>
                            <td>{{ $p->kelompokKeahlian->nama_kbk }}</td>
                            <!-- Menampilkan nama kelompok keahlian dari relasi -->
                            <td>{{ $p->email_inventor }}</td>
                            <td>
                                @if ($p->status === 'Tervalidasi')
                                    <span class="badge bg-label-success me-1">{{ $p->status }}</span>
                                @else
                                    <span class="badge bg-label-warning me-1">{{ $p->status }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('show.produk', $p->id) }}" class="btn btn-sm btn-success">
                                    <i class='bx bxs-show'></i>
                                </a>
                            </td>
                            <td>
                                <form id="form-validasi-{{ $p->id }}"
                                    action="{{ route('validate.produk', $p->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="checkbox" id="status_validasi-{{ $p->id }}" name="status"
                                        value="Tervalidasi" class="btn-checkbox bx bx-check"
                                        {{ $p->status == 'Tervalidasi' ? 'checked' : '' }}>
                                </form>
                            </td>
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
