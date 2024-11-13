<div class="container-fluid flex-grow-1 container-p-y">
    <div class="card p-3 mt-4 mb-5">

        <div class="container">
            <h2>Laporan Produk dan Penelitian</h2>

            <!-- Form Filter -->
            <form method="GET" action="{{ route('report.index') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <label for="tahun_awal">Tahun Awal</label>
                        <input type="number" name="tahun_awal" id="tahun_awal" class="form-control"
                            placeholder="Contoh: 2020" value="{{ request('tahun_awal') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="tahun_akhir">Tahun Akhir</label>
                        <input type="number" name="tahun_akhir" id="tahun_akhir" class="form-control"
                            placeholder="Contoh: 2023" value="{{ request('tahun_akhir') }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-3">Filter</button>
                        <a href="{{ route('report.index', array_merge(request()->all(), ['download' => 'csv'])) }}"
                            class="btn btn-success ml-2">Unduh CSV</a>
                    </div>
                </div>
            </form>

            <!-- Tabel Data Produk -->
            <h3>Data Produk</h3>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama KBK</th>
                            <th>Nama Produk</th>
                            <th>Inventor</th>
                            <th>Tanggal Submit</th>
                            <th>Tanggal Granted</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($produk as $item)
                            <tr>
                                <td>{{ $item->kelompokKeahlian->nama_kbk ?? '-' }}</td>
                                <td>{{ $item->nama_produk }}</td>
                                <td>{{ $item->inventor }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_submit)->format('d-m-Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_granted)->format('d-m-Y') }}</td>
                                <td>{{ $item->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Tabel Data Penelitian -->
            <h3>Data Penelitian</h3>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama KBK</th>
                            <th>Judul Penelitian</th>
                            <th>Penulis</th>
                            <th>Penulis Korespondensi</th>
                            <th>Tanggal Publikasi</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($penelitian as $item)
                            <tr>
                                <td>{{ $item->kelompokKeahlian->nama_kbk ?? '-' }}</td>
                                <td>{{ $item->judul }}</td>
                                <td>{{ $item->penulis }}</td>
                                <td>{{ $item->penulis_korespondensi }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_publikasi)->format('d-m-Y') }}</td>
                                <td>{{ $item->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <div class="card p-3">



        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h4>Status Validasi Produk</h4>
                    <div id="produk-status-chart"></div>
                </div>
                <div class="col-md-6">
                    <h4>Status Validasi Penelitian</h4>
                    <div id="penelitian-status-chart"></div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <h4>Produk per Tahun</h4>
                    <div id="produk-tahun-chart"></div>
                </div>
                <div class="col-md-6">
                    <h4>Penelitian per Tahun</h4>
                    <div id="penelitian-tahun-chart"></div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Data untuk chart status produk
                var produkStatusOptions = {
                    chart: {
                        type: 'pie'
                    },
                    series: [{{ $prdk_valid }}, {{ $prdk_nonvalid }}],
                    labels: ['Tervalidasi', 'Belum Divalidasi'],
                    title: {
                        text: 'Status Validasi Produk'
                    }
                };
                var produkStatusChart = new ApexCharts(document.querySelector("#produk-status-chart"),
                    produkStatusOptions);
                produkStatusChart.render();

                // Data untuk chart status penelitian
                var penelitianStatusOptions = {
                    chart: {
                        type: 'pie'
                    },
                    series: [{{ $pnltan_valid }}, {{ $pnltan_nonvalid }}],
                    labels: ['Tervalidasi', 'Belum Divalidasi'],
                    title: {
                        text: 'Status Validasi Penelitian'
                    }
                };
                var penelitianStatusChart = new ApexCharts(document.querySelector("#penelitian-status-chart"),
                    penelitianStatusOptions);
                penelitianStatusChart.render();

                // Data untuk chart produk per tahun
                var produkTahunOptions = {
                    chart: {
                        type: 'bar'
                    },
                    series: [{
                        name: 'Jumlah Produk',
                        data: {!! json_encode(array_values($prdk_tahun->toArray())) !!}
                    }],
                    xaxis: {
                        categories: {!! json_encode(array_keys($prdk_tahun->toArray())) !!}
                    },
                    title: {
                        text: 'Produk per Tahun'
                    }
                };
                var produkTahunChart = new ApexCharts(document.querySelector("#produk-tahun-chart"),
                    produkTahunOptions);
                produkTahunChart.render();

                // Data untuk chart penelitian per tahun
                var penelitianTahunOptions = {
                    chart: {
                        type: 'bar'
                    },
                    series: [{
                        name: 'Jumlah Penelitian',
                        data: {!! json_encode(array_values($plt_tahun->toArray())) !!}
                    }],
                    xaxis: {
                        categories: {!! json_encode(array_keys($plt_tahun->toArray())) !!}
                    },
                    title: {
                        text: 'Penelitian per Tahun'
                    }
                };
                var penelitianTahunChart = new ApexCharts(document.querySelector("#penelitian-tahun-chart"),
                    penelitianTahunOptions);
                penelitianTahunChart.render();
            });
        </script>

    </div>

</div>
