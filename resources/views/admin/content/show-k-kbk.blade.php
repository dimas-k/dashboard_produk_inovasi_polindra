<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card p-2">
        <h5 class="card-header border-3 w-100 mb-2"><i class='bx bx-table me-2'></i>Data {{ $k_kbk->nama_lengkap }}</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-borderless">
                <tr>
                    <th>Nama Lengkap</th>
                    <td>: {{ $k_kbk->nama_lengkap }}</td>
                </tr>
                <tr>
                    <th>nip</th>
                    <td>: {{ $k_kbk->nip }}</td>
                </tr>
                <tr>
                    <th>Jabatan</th>
                    <td>: {{ $k_kbk->jabatan }}</td>
                </tr>
                <tr>
                    <th>Ketua KBK</th>
                    <td>: {{ $k_kbk->kbk ? $k_kbk->kbk->nama_kbk : 'Tidak ada' }}</td>
                </tr>
                <tr>
                    <th>email</th>
                    <td>: {{ $k_kbk->email }}</td>
                </tr>
                <tr>
                    <th>no handphone</th>
                    <td>: {{ $k_kbk->no_hp }}</td>
                </tr>
                <tr>
                    <th>username</th>
                    <td>: {{ $k_kbk->username }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>