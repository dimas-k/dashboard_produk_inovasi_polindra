<nav class="navbar navbar-expand-lg bg-primary navbar-dark sticky-top py-lg-0 px-lg-5 wow fadeIn" data-wow-delay="0.1s">
    <a href="#" class="navbar-brand ms-3 d-lg-none">MENU</a>
    <button type="button" class="navbar-toggler me-3" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav d-flex w-100 p-3 p-lg-0">
            <a href="/" class="nav-item nav-link {{ Request::is('/') ? 'active' : '' }}">Home</a>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle {{ Request::is('dashboard/kelompok-bidang-keahlian/*') ? 'active' : '' }}" data-bs-toggle="dropdown">Kelompok Bidang Keahlian</a>
                <div class="dropdown-menu border-0 rounded-0 rounded-bottom m-0">
                    @foreach ($kbk as $i)
                    <a href="{{ route('dashboard.penelitian', $i->nama_kbk) }}" 
                       class="dropdown-item {{ Request::is('dashboard/kelompok-bidang-keahlian/' . $i->nama_kbk) ? 'active' : '' }}">
                        {{ $i->nama_kbk }}
                    </a>
                    @endforeach
                </div>
            </div>  
            <a href="/dashboard/kontak" class="nav-item nav-link {{ Request::is('dashboard/kontak') ? 'active' : '' }}">Kontak Kami</a>
            <a href="https://siki.polindra.ac.id/" class="nav-item nav-link" target="_blank">Sentra KI</a>
            <a href="/login" class="nav-item nav-link {{ Request::is('login') ? 'active' : '' }}" target="_blank">Login</a>
        </div>
    </div>
</nav>

