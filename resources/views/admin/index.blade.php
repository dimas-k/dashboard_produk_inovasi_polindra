<!doctype html>

<html lang="en" class="light-style layout-menu-fixed layout-wide" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('assets-admin/') }}" data-template="vertical-menu-template-free" data-style="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Admin D-PROIN | Dashboard</title>

    <meta name="description" content="" />
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets-admin/img/logo-polindra.png') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets-admin/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets-admin/vendor/css/core.css') }}"
        class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets-admin/vendor/css/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets-admin/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets-admin/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="{{ asset('assets-admin/vendor/js/helpers.js') }}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('assets-admin/js/config.js') }}"></script>

    {{-- <style>
        #myChartPrdk, #myChartPlt {
        max-width: 600px;
        max-height: 400px;
        /* margin: auto; */
    }
    </style> --}}
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            @include('admin.layouts.sidebar')
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                @include('admin.layouts.header')

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    @include('admin.content.dashboard')
                </div>
                <!-- / Content -->



                <div class="content-backdrop fade"></div>
            </div>
            <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="{{ asset('assets-admin/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets-admin/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets-admin/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets-admin/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets-admin/vendor/js/menu.js') }}"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="{{ asset('assets-admin/js/main.js') }}"></script>

    <!-- Page JS -->

    <!-- Place this tag before closing body tag for github widget button. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Import ApexCharts via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('myChartPrdk').getContext('2d');

            // Labels dan data dari $prdk_tahun
            const tahunLabels = @json($prdk_tahun->keys()); // Tahun sebagai label
            const produkData = @json($prdk_tahun->values()); // Jumlah produk sebagai data

            const myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: tahunLabels,
                    datasets: [{
                        label: 'Jumlah Produk Tervalidasi Per Tahun Berdasarkan Tahun Granted',
                        data: produkData,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                        }
                    },
                    elements: {
                        line: {
                            tension: 0.5
                        }
                    },
                    scales: {
                        y: {
                            suggestedMin: 0,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    categoryPercentage: 0.5,
                    transitions: {
                        show: {
                            animations: {
                                x: {
                                    from: 1
                                },
                                y: {
                                    from: 1
                                }
                            }
                        },
                        hide: {
                            animations: {
                                x: {
                                    to: 10
                                },
                                y: {
                                    to: 10
                                }
                            }
                        }
                    }
                },
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('myChartPlt').getContext('2d');

            // Labels dan data dari $prdk_tahun
            const tahunLabels = @json($plt_tahun->keys()); // Tahun sebagai label
            const penelitianData = @json($plt_tahun->values()); // Jumlah produk sebagai data

            const myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: tahunLabels,
                    datasets: [{
                        label: 'Jumlah Penelitian Tervalidasi Per Tahun Berdasarkan Tahun Publikasi',
                        data: penelitianData,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                        }
                    },
                    elements: {
                        line: {
                            tension: 0.5
                        }
                    },
                    scales: {
                        y: {
                            suggestedMin: 0,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    categoryPercentage: 0.5,
                    transitions: {
                        show: {
                            animations: {
                                x: {
                                    from: 1
                                },
                                y: {
                                    from: 1
                                }
                            }
                        },
                        hide: {
                            animations: {
                                x: {
                                    to: 10
                                },
                                y: {
                                    to: 10
                                }
                            }
                        }
                    }
                },
            });
        });

        function toggleChart() {
            const selectedValue = document.getElementById('chartSelect').value;

            if (selectedValue === 'produk') {
                document.getElementById('produkChartContainer').style.display = 'block';
                document.getElementById('penelitianChartContainer').style.display = 'none';
            } else if (selectedValue === 'penelitian') {
                document.getElementById('produkChartContainer').style.display = 'none';
                document.getElementById('penelitianChartContainer').style.display = 'block';
            }
        }
        document.getElementById('chartSelect').addEventListener('change', toggleChart);
        window.onload = toggleChart;
    </script>
    <script>
        let penelitianChart, produkChart;

        function initializeCharts() {
            penelitianChart = new ApexCharts(document.querySelector("#penelitian-chart"), {
                chart: {
                    type: 'line'
                },
                series: [{
                    name: 'Penelitians',
                    data: []
                }],
                xaxis: {
                    type: 'datetime'
                },
                title: {
                    text: 'Laporan Penelitian'
                }
            });
            penelitianChart.render();

            produkChart = new ApexCharts(document.querySelector("#produk-chart"), {
                chart: {
                    type: 'line'
                },
                series: [{
                    name: 'Produks',
                    data: []
                }],
                xaxis: {
                    type: 'datetime'
                },
                title: {
                    text: 'Laporan Produk'
                }
            });
            produkChart.render();
        }

        function fetchData() {
            const startDate = document.getElementById("start_date").value;
            const endDate = document.getElementById("end_date").value;

            // Fetch data untuk Penelitian
            fetch(/report/penelitian ? start_date = $ {
                    startDate
                } & end_date = $ {
                    endDate
                })
                .then(response => response.json())
                .then(data => {
                    const formattedData = data.map(item => [new Date(item.date).getTime(), item.count]);
                    penelitianChart.updateSeries([{
                        name: 'Penelitians',
                        data: formattedData
                    }]);
                });

            // Fetch data untuk Produk
            fetch(/report/produk ? start_date = $ {
                    startDate
                } & end_date = $ {
                    endDate
                })
                .then(response => response.json())
                .then(data => {
                    const formattedData = data.map(item => [new Date(item.date).getTime(), item.count]);
                    produkChart.updateSeries([{
                        name: 'Produks',
                        data: formattedData
                    }]);
                });
        }

        // Initialize charts on page load
        document.addEventListener("DOMContentLoaded", function() {
            initializeCharts();
        });
    </script>

</body>

</html>
