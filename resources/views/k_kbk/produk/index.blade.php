<!doctype html>

<html lang="en" class="light-style layout-menu-fixed layout-wide" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('assets-admin/') }}" data-template="vertical-menu-template-free" data-style="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Custom CSS for z-index -->
    <style>
        .swal2-container {
            z-index: 2000 !important;
            /* SweetAlert z-index lebih tinggi dari Bootstrap modal */
        }
    </style>



    <title>D-PROIN | Ketua KBK | produk</title>

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
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            @include('k_kbk.layouts.sidebar')
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                @include('k_kbk.layouts.header')

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    @include('k_kbk.content.produk')
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
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        window.deleteConfirm = function(e) {
            e.preventDefault(); // Mencegah pengiriman form

            var form = $(e.target).closest('form'); // Mengambil form terkait dan membungkusnya dengan jQuery

            Swal.fire({
                title: "Apakah Kamu yakin ?",
                text: "Produk ini akan kamu hapus!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: form.attr('action'), // Mengambil URL dari atribut action
                        type: 'POST',
                        data: form.serialize() + '&_method=DELETE', // Menggunakan serialize dari jQuery
                        success: function(response) {
                            location.reload(); // Untuk memuat ulang halaman
                            // Tampilkan alert sukses
                            Swal.fire({
                                title: 'Dihapus!',
                                text: 'Produk Telah Berhasil dihapus.',
                                icon: 'success',
                                confirmButtonText: 'OKE'
                            })
                        },
                        error: function(xhr) {
                            // Tangani kesalahan
                            Swal.fire({
                                title: 'Error!',
                                text: 'There was a problem deleting the item.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        }
    </script>

    <script>
        $(document).ready(function() {
            $('#uploadForm').on('submit', function(e) {
                e.preventDefault(); // Mencegah submit form secara langsung

                // Mengambil nilai input
                const kbk_id = $('#exampleFormControlSelect1').val();
                const nama_produk = $('#nama_produk').val();
                const deskripsi = $('textarea[name="deskripsi"]').val();
                const gambar = $('#gambar').val();
                const inventor = $('#inventor').val();
                const anggota_inventor = $('textarea[name="anggota_inventor"]').val();
                const email_inventor = $('#email').val();
                const lampiran = $('#lampiran').val();

                // Regex untuk validasi file extensions
                const gambarExtensions = /(\.jpg|\.jpeg|\.png)$/i;
                const lampiranExtensions = /(\.jpg|\.jpeg|\.png|\.pdf|\.docx)$/i;

                // Validasi input kosong
                if (!kbk_id || !nama_produk || !deskripsi || !gambar || !inventor || !anggota_inventor || !
                    email_inventor || !lampiran) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Semua input harus diisi!',
                    });
                    return;
                }

                // Validasi format file gambar
                if (!gambarExtensions.exec(gambar)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Gambar harus dalam format JPG, JPEG, atau PNG!',
                    });
                    return;
                }

                // Validasi format file lampiran
                if (!lampiranExtensions.exec(lampiran)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Lampiran harus dalam format JPG, JPEG, PNG, PDF, atau DOCX!',
                    });
                    return;
                }

                // Jika semua validasi lolos, submit form
                Swal.fire({
                    title: 'Simpan Data?',
                    text: "Apakah Anda yakin ingin menyimpan data produk ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit form secara manual
                        $('#basicModal').modal('hide');

                        // Setelah modal ditutup, submit form
                        setTimeout(function() {
                            $('#uploadForm')[0].submit();

                            // Tampilkan alert setelah submit
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Selamat, produk Anda telah berhasil ditambahkan!',
                            });
                        },
                        500);
                    }
                });
            });
        });
    </script>

<script>
    $(document).ready(function() {
        // Loop untuk setiap form berdasarkan ID produk
        @foreach ($produks as $p)
            $('#editForm_{{ $p->id }}').on('submit', function(e) {
                e.preventDefault(); // Mencegah submit form secara langsung

                // Ambil ID produk untuk elemen yang sedang diproses
                const id = '{{ $p->id }}';

                // Mengambil nilai input berdasarkan ID produk
                const nama_produk = $('#nama_produk_' + id).val();
                const deskripsi = $('#deskripsi_' + id).val();
                const anggota = $('#anggota_' + id).val();
                const email = $('#email_' + id).val();
                const inventor = $('#inventor_' + id).val();
                const gambar = $('#gambar_' + id)[0].files.length ? $('#gambar_' + id)[0].files[0].name : '';
                const lampiran = $('#lampiran_' + id)[0].files.length ? $('#lampiran_' + id)[0].files[0].name : '';

                // Regex untuk validasi file extensions
                const gambarExtensions = /(\.jpg|\.jpeg|\.png)$/i;
                const lampiranExtensions = /(\.jpg|\.jpeg|\.png|\.pdf|\.docx)$/i;

                // Validasi input kosong
                if (!nama_produk || !deskripsi || !anggota || !email || !inventor || !gambar || !lampiran) {

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Semua input harus diisi!',
                    });
                    return;
                }

                // Validasi format file gambar
                if (gambar && !gambarExtensions.exec(gambar)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Gambar produk harus dalam format JPG, JPEG, atau PNG!',
                    });
                    return;
                }

                // Validasi format file lampiran
                if (lampiran && !lampiranExtensions.exec(lampiran)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Lampiran harus dalam format JPG, JPEG, PNG, PDF, atau DOCX!',
                    });
                    return;
                }

                // Jika semua validasi lolos, submit form
                Swal.fire({
                    title: 'Simpan Data?',
                    text: "Apakah Anda yakin ingin update data produk ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Tutup modal sebelum submit form
                        $('#basicModal{{ $p->id }}').modal('hide');

                        // Submit form yang terkait berdasarkan ID produk
                        $('#editForm_{{ $p->id }}')[0].submit();

                        // Tampilkan alert setelah submit sukses
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Data berhasil diupdate!'
                        });
                    }
                });
            });
        @endforeach
    });
</script>






</body>

</html>
