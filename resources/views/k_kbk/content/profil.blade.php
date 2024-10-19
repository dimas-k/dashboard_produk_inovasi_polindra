<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card p-3">
        <h5 class="card-header border-3 w-100 mb-2"><i class='bx bx-table me-2'></i>Data
            {{ auth()->user()->nama_lengkap }}
        </h5>
        <div class="table-responsive text-nowrap">

            <table class="table table-borderless">
                <tr>
                    <th>Nama lengkap</th>
                    <td>: {{ auth()->user()->nama_lengkap }}</td>
                </tr>
                <tr>
                    <th>nip</th>
                    <td>: {{ auth()->user()->nip }}</td>
                </tr>
                <tr>
                    <th>jabatan</th>
                    <td>: {{ auth()->user()->jabatan }}</td>
                </tr>
                <tr>
                    <th>email</th>
                    <td>: {{ auth()->user()->email }}</td>
                </tr>
                <tr>
                    <th>no handphone</th>
                    <td>: {{ auth()->user()->no_hp }}</td>
                </tr>
                <tr>
                    <th>Kelompok Keahlian</th>
                    <td>:
                        {{ auth()->user()->kelompokKeahlian ? auth()->user()->kelompokKeahlian->nama_kbk : 'Tidak ada' }}
                    </td>
                </tr>
                <tr>
                    <th>username</th>
                    <td>: {{ auth()->user()->username }}</td>
                </tr>

            </table>

            <a href="/k-kbk/profil/edit" class="btn btn-primary m-3">Edit data profil</a>
        </div>
    </div>
</div>
