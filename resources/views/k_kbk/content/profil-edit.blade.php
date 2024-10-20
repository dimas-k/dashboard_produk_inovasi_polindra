<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card p-3">
        <h5 class="card-header border-3 w-100 mb-2"><i class='bx bx-table me-2'></i>Data
            {{ auth()->user()->nama_lengkap }}</h5>

        <div class="table-responsive text-nowrap">
            <form action="{{ route('update.profil', auth()->user()->id) }}" method="post" id="updateForm">
                @method('PUT')
                @csrf
                <table class="table table-borderless">
                    <th>Nama Lengkap</th>
                    <td><input type="text" class="form-control " id="nama_lengkap"name="nama_lengkap"
                            value="{{ auth()->user()->nama_lengkap }}">
                        @error('nama_lengkap')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </td>
                    <tr>
                        <th>nip</th>
                        <td><input type="text" class="form-control" id="nip"name="nip"
                                value="{{ auth()->user()->nip }}">
                            @error('nip')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th>jabatan</th>
                        <td>
                            <input type="text" class="form-control" id="jabatan"name="jabatan"
                                value="{{ 'Ketua KBK '. auth()->user()->jabatan }}">
                            @error('jabatan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </td>
                        </td>
                    </tr>
                    <tr>
                        <th>email</th>
                        <td>
                            <input type="text" class="form-control" id="email"name="email"
                                value="{{ auth()->user()->email }}">
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th>no handphone</th>
                        <td>
                            <input type="number" class="form-control" id="no_hp"name="no_hp"
                                value="{{ auth()->user()->no_hp }}">
                            @error('no_hp')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th>Kelompok Bidang Keahlian</th>
                        <td>
                            <input type="text" class="form-control" id="kbk"name="jabatan"
                                value="{{ auth()->user()->kelompokKeahlian ? auth()->user()->kelompokKeahlian->nama_kbk : 'Tidak ada' }}"
                                readonly>
                            @error('jabatan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th>username</th>
                        <td>
                            <input type="text" class="form-control" id="username"name="username"
                                value="{{ auth()->user()->username }}">
                            @error('username')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </td>
                    </tr>
                    {{-- <tr>
                        <th>Password</th>
                        <td>
                            <div class="form-password-toggle">
                                <div class="input-group input-group-merge">
                                    <input type="password" class="form-control" id="pass"name="password"
                                        value="" aria-describedby="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;">
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <span class="text-danger"><i class='bx bxs-error me-1'></i>Jika password tidak diubah, maka jangan diisi</span>
                        </td>
                    </tr> --}}
                    <tr>
                        <th><label class="form-label" for="password_last">Password Terakhir anda</label></th>
                        <td>
                            <div class="form-password-toggle">
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password_last" class="form-control" name="password_last"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"/>
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    @error('password_last')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </td>
                    </tr>

                </table>
                <button type="submit" class="btn btn-primary m-2">Update</button>
            </form>
        </div>
    </div>
</div>

{{-- <div class="container-xxl flex-grow-1 container-p-y">
    <div class="card p-3">
        <h5 class="card-header border-3 w-100 mb-2"><i class='bx bx-table me-2'></i>Data
            {{ auth()->user()->nama_lengkap }}</h5>

        <div class="table-responsive text-nowrap">
            <form action="{{ route('update.profil', auth()->user()->id) }}" method="post" id="updateForm">
                @method('PUT')
                @csrf
                <table class="table table-borderless">
                    <th>Nama Lengkap</th>
                    <td><input type="text" class="form-control " id="nama_lengkap"name="nama_lengkap"
                            value="{{ auth()->user()->nama_lengkap }}">
                        @error('nama_lengkap')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </td>
                    <!-- Field lainnya -->
                    <tr>
                        <th>Password Terakhir</th>
                        <td>
                            <input type="password" class="form-control" id="password_last" name="password_last"
                                placeholder="Masukkan password terakhir Anda" required>
                            @error('password_last')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </td>
                    </tr>
                </table>
                <button type="submit" class="btn btn-primary m-2">Update</button>
            </form>
        </div>
    </div>
</div> --}}
