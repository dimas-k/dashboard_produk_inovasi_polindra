<nav class="navbar navbar-expand-lg bg-primary navbar-dark sticky-top py-lg-0 px-lg-5 wow fadeIn" data-wow-delay="0.1s">
    <a href="#" class="navbar-brand ms-3 d-lg-none">MENU</a>
    <button type="button" class="navbar-toggler me-3" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav d-flex w-100 p-3 p-lg-0">
            <a href="/dashboard" class="nav-item nav-link">Home</a>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">KBK</a>
                <div class="dropdown-menu border-0 rounded-0 rounded-bottom m-0">
                    {{-- <a href="/dashboard/penelitian" class="dropdown-item">KBK Sistem Informasi</a> --}}
                    {{-- <a href="/dashboard/penelitian" class="dropdown-item">KBK Rekayasa Perangkat Lunak dan
                        Pengetahuan</a> --}}
                    {{-- <a href="/dashboard/penelitian" class="dropdown-item">⁠KBK Sistem Komputer dan Jaringan</a> --}}
                    {{-- <a href="/dashboard/penelitian" class="dropdown-item">KBK Sains Data</a> --}}
                    {{-- <a href="/dashboard/penelitian" class="dropdown-item">KBK Fundamental and Management Nursing</a> --}}
                    {{-- <a href="/dashboard/penelitian" class="dropdown-item">⁠KBK Clinical Care Nursing</a> --}}
                    {{-- <a href="/dashboard/penelitian" class="dropdown-item">KBK Mental Health and Community Nursing</a> --}}
                    {{-- <a href="/dashboard/penelitian" class="dropdown-item">KBK Perancangan dan Manufaktur</a> --}}
                    {{-- <a href="/dashboard/penelitian" class="dropdown-item">⁠KBK Rekayasa Material</a> --}}
                    {{-- <a href="/dashboard/penelitian" class="dropdown-item">⁠KBK RHVAC</a> --}}
                    {{-- <a href="/dashboard/penelitian" class="dropdown-item">⁠KBK Instrumentasi dan Kontrol</a> --}}

                    @foreach ($kbk as $i)
                    <a href="{{ route('dashboard/penelitian/',$i->id) }}" class="dropdown-item">{{ $i->nama_kbk }}⁠</a>
                    @endforeach
                </div>
            </div>  
            <a href="/dashboard/contact" class="nav-item nav-link">Contact Us</a>

            <a href="/login" class="nav-item nav-link" target="_blank">Login</a>

        </div>
    </div>
</nav>
