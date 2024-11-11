<div class="container-fluid page-header py-5 mb-5 wow fadeIn" data-wow-delay="0.001s">
    <div class="container text-center py-5">
        <h1 class="display-4 text-white animated slideInDown mb-3">
            Produk & Penelitian Dosen
            @if ($p_dosen->isNotEmpty())
                {{ $p_dosen[0]->inventor }}
            @elseif($plt_dosen->isNotEmpty())
                {{ $plt_dosen[0]->penulis }}
            @else
                {{ $dosen }}
            @endif
        </h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a class="text-white" href="/">Home</a></li>
                <li class="breadcrumb-item text-primary active" aria-current="page">
                    Produk dan Penelitian
                    @if ($p_dosen->isNotEmpty())
                        {{ $p_dosen[0]->inventor }}
                    @elseif($plt_dosen->isNotEmpty())
                        {{ $plt_dosen[0]->penulis }}
                    @else
                        {{ $dosen }}
                    @endif
                </li>
            </ol>
        </nav>
    </div>
</div>

<section class="site-section" id="section-resume">
    <div class="container">
        <div class="row">

            <!-- Bagian Produk -->
            <div class="container-fluid">
                <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: auto;">
                    <h6 class="section-title bg-white text-center text-primary px-3">Produk Dosen</h6>
                </div>
            </div>

            <div class="wow fadeInUp" data-wow-delay="0.1s">
                @if ($p_dosen->isNotEmpty())
                    @foreach ($p_dosen as $g_produk)
                        <div class="col-12 d-flex flex-wrap align-items-start mb-4">
                            <!-- Image Section -->
                            <div class="col-md-4 col-sm-12 mb-4">
                                <a href="{{ route('detail.produk', $g_produk->nama_produk) }}" class="link-underline link-underline-opacity-0">
                                    <img src="{{ asset('storage/' . $g_produk->gambar) }}" alt="gambar produk {{ $g_produk->nama_produk }}" style="width:100%; height:auto;">
                                </a>
                            </div>
                            <!-- Text Section -->
                            <div class="col-md-6 col-sm-12 ps-md-4">
                                <div>
                                    <h3><a href="{{ route('detail.produk', ['nama_produk' => $g_produk->nama_produk]) }}">{{ $g_produk->nama_produk }}</a></h3>
                                    
                                    @php
                                        $limitedDescription = \Illuminate\Support\Str::limit($g_produk->deskripsi, 200);
                                    @endphp
                                    <p>{{ $limitedDescription }}
                                        @if (strlen($g_produk->deskripsi) > 200)
                                            <a href="{{ route('detail.produk', ['nama_produk' => $g_produk->nama_produk]) }}" class="link-offset-3-hover link-underline-opacity-75-hover">...Selengkapnya</a>
                                        @endif
                                    </p>
                                    <p><strong>Tahun Granted:</strong> {{ \Carbon\Carbon::parse($g_produk->tanggal_granted)->format('Y') }}</p>
                                    <div class="article-meta-sm mt-2">
                                        <a class="link-secondary" href="{{ route('dashboard.penelitian', ['nama_kbk' => $g_produk->KelompokKeahlian->nama_kbk]) }}">
                                            {{ $g_produk->kelompokKeahlian->nama_kbk }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-center mb-5">Dosen ini belum memiliki produk</p>
                @endif
            </div>

            {{ $p_dosen->links() }}

            <!-- Bagian Penelitian -->
            <div class="container-fluid">
                <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: auto;">
                    <h6 class="section-title bg-white text-center text-primary px-3">Penelitian Dosen</h6>
                </div>
            </div>

            @if ($plt_dosen->isNotEmpty())
                @foreach ($plt_dosen as $g_penelitian)
                    <div class="col-12 d-flex flex-wrap align-items-start mb-4 wow fadeInUp" data-wow-delay="0.1s">
                        <!-- Image Section -->
                        <div class="col-md-4 col-sm-12 mb-4">
                            <a href="{{ route('detail.penelitian', $g_penelitian->judul) }}" class="link-underline link-underline-opacity-0">
                                <img src="{{ asset('storage/' . $g_penelitian->gambar) }}" alt="gambar penelitian {{ $g_penelitian->judul }}" style="width:100%; height:auto;">
                            </a>
                        </div>
                        <!-- Text Section -->
                        <div class="col-md-6 col-sm-12 ps-md-4">
                            <div>
                                <h3><a href="{{ route('detail.penelitian', ['judul' => $g_penelitian->judul]) }}">{{ $g_penelitian->judul }}</a></h3>
                                @php
                                    $limitedDescription2 = \Illuminate\Support\Str::limit($g_penelitian->abstrak, 250);
                                @endphp
                                <p>{{ $limitedDescription2 }}
                                    @if (strlen($g_penelitian->abstrak) > 250)
                                        <a href="{{ route('detail.penelitian', ['judul' => $g_penelitian->judul]) }}" class="link-offset-3-hover link-underline-opacity-75-hover">...Selengkapnya</a>
                                    @endif
                                </p>
                                <p><strong>Tahun Publikasi:</strong> {{ \Carbon\Carbon::parse($g_penelitian->tanggal_publikasi)->format('Y') }}</p>
                                <div class="article-meta-sm mt-2">
                                    <a class="link-secondary" href="{{ route('dashboard.penelitian', ['nama_kbk' => $g_penelitian->KelompokKeahlian->nama_kbk]) }}">
                                        {{ $g_penelitian->kelompokKeahlian->nama_kbk }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-center mb-5">Dosen ini belum memiliki penelitian</p>
            @endif

            {{ $plt_dosen->links() }}
        </div>
    </div>
</section>