<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="index.html" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('assets-admin/img/logo-item-bg.png') }}" alt=""
                    style="width: 110px; heigh: 50px">
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm d-flex align-items-center justify-content-center"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboards -->

        <li class="menu-item">
            <a href="/admin/dashboard" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-smile"></i>
                <div class="text-truncate" data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="/admin/kelompok-bidang-keahlian" class="menu-link">
                <i class='menu-icon tf-icons bx bxs-cog'></i>
                <div class="text-truncate" data-i18n="KBK">Kelompok Bidang Keahlian</div>
            </a>
        </li>
        <!-- Layouts -->

        <!-- Front Pages -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class='menu-icon tf-icons bx bxs-user-circle'></i>
                <div class="text-truncate" data-i18n="Pengguna">Pengguna</div>
                
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="/admin/admin-page" class="menu-link">
                        <div class="text-truncate" data-i18n="Admin">Admin</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="/admin/ketua-kbk" class="menu-link">
                        <div class="text-truncate" data-i18n="KetuaKBK">Ketua KBK</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item">
            <a href="javascript:void(o)" class="menu-link menu-toggle">
                <i class='menu-icon tf-icons bx bxs-cog'></i>
                <div class="text-truncate" data-i18n="Produk">Produk Inovasi</div>
            </a>
            @foreach ($kbk_navigasi as $i)
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="/admin/produk-inovasi/{{ $i->id }}" class="menu-link">
                            <div class="text-truncate" data-i18n="{{ $i->nama_kbk }}">{{ $i->nama_kbk }}</div>
                        </a>
                    </li>
                </ul>
            @endforeach
        </li>
        <!-- Apps & Pages -->
    </ul>
</aside>




