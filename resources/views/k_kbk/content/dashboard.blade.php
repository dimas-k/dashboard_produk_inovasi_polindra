<div class="container-fluid flex-grow-1 container-p-y">
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
