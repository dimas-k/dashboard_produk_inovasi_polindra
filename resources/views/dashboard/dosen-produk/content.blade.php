<div class="container-fluid page-header py-5 mb-5 wow fadeIn" data-wow-delay="0.001s">
    <div class="container text-center py-5">
        <h1 class="display-4 text-white animated slideInDown mb-3">Produk Dosen</h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a class="text-white" href="/">Home</a></li>
                <li class="breadcrumb-item text-primary active" aria-current="page">Produk {{ $p_dosen[0]->inventor }}
                </li>
            </ol>
        </nav>
    </div>
</div>

<section class="site-section " id="section-resume">
    <div class="container">
        <div class="row">
            <div class="container-fluid">
                <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: auto;">
                    <h6 class="section-title bg-white text-center text-primary px-3"> Produk Dosen
                    </h6>
                    <h1 class="display-6 mb-5">{{ $p_dosen[0]->inventor }}</h1>
                </div>
            </div>

            <div class="container-fluid">
                
            </div>

            @foreach ($p_dosen as $index => $g_produk)
                <div class="col-12 d-flex flex-wrap align-items-start mb-4">
                    <!-- Image Section -->
                    <div class="col-md-4 col-sm-12 mb-4">
                        <a href="{{ route('detail.produk', $g_produk->nama_produk) }}"
                           class="link-underline link-underline-opacity-0">
                            <img src="{{ asset('storage/' . $g_produk->gambar) }}"
                                 alt="gambar produk {{ $g_produk->nama_produk }}"
                                 style="width:100%; height:auto;">
                        </a>
                    </div>
                    <!-- Text Section -->
                    <div class="col-md-6 col-sm-12 ps-md-4">
                        <div>
                            <h3><a href="{{ route('detail.produk', ['nama_produk' => $g_produk->nama_produk]) }}">{{ $g_produk->nama_produk }}</a></h3>
                            @php
                                $limitedDescription = \Illuminate\Support\Str::limit($g_produk->deskripsi, 120, '');
                            @endphp
                            <p style="white-space: normal; word-wrap: break-word;">
                                {{ $limitedDescription }}
                                @if (strlen($g_produk->deskripsi) > 120)
                                    <a href="{{ route('detail.produk', ['nama_produk' => $g_produk->nama_produk]) }}"
                                       class="link-offset-3-hover link-underline-opacity-75-hover">
                                        ...Selengkapnya</a>
                                @endif
                            </p>
                            <div class="article-meta-sm mt-2">
                                <div><a class="link-secondary" href="{{ route('dashboard.penelitian', ['nama_kbk' => $g_produk->KelompokKeahlian->nama_kbk]) }}">{{ $g_produk->kelompokKeahlian->nama_kbk }}</a></div>
                                <div>{{ $g_produk->kelompokKeahlian->nama_kbk }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach


        </div>
        {{ $p_dosen->links() }}
    </div>
</section>
