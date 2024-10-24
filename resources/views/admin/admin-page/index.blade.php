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



    <title>Admin D-PROIN | Admin</title>

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
            @include('admin.layouts.sidebar')
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                @include('admin.layouts.header')

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    @include('admin.content.admin-page')
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
        $(document).ready(function() {
            $('#uploadForm').submit(function(e) {
                e.preventDefault();

                // Ambil semua inputan
                var nama_lengkap = $('#nama_lengkap').val();
                var nip = $('#nip').val();
                var jabatan = $('#jabatan').val();
                var foto = $('#foto').val();
                var no_hp = $('#no_hp').val();
                var email = $('#email').val();
                var username = $('#username').val();
                var password = $('#password').val();

                // Cek jika inputan ada yang kosong
                if (!nama_lengkap || !nip || !jabatan || !foto || !no_hp || !email || !username || !
                    password) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Semua inputan harus diisi!',
                    });
                    return false;
                }

                // Validasi file pas foto (hanya jpg, jpeg, png)
                var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
                if (!allowedExtensions.exec(foto)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Pas Foto harus berformat jpg, jpeg, atau png!',
                    });
                    return false;
                }

                // Konfirmasi untuk menyimpan data
                Swal.fire({
                    title: 'Simpan Data?',
                    text: "Apakah Anda yakin ingin menyimpan data ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Tutup modal sebelum submit form
                        $('#basicModal').modal('hide');

                        // Submit form secara manual
                        $.ajax({
                            url: $('#uploadForm').attr('action'),
                            type: 'POST',
                            data: new FormData(
                                this), // Menggunakan FormData untuk file upload
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                // Menyimpan pesan sukses di local storage
                                localStorage.setItem('successMessage',
                                    'Selamat, data telah berhasil ditambahkan!');

                                // Reload halaman
                                location.reload();
                            },
                            error: function(xhr) {
                                // Mengambil pesan error dari respons server
                                let errorMessage =
                                    'Terjadi kesalahan saat menyimpan data.';

                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON
                                        .message; // Mengambil pesan spesifik
                                }

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: errorMessage,
                                });
                            }
                        });
                    }
                });
            });

            // Menampilkan alert setelah halaman dimuat
            if (localStorage.getItem('successMessage')) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: localStorage.getItem('successMessage'),
                }).then(() => {
                    // Menghapus pesan dari local storage setelah ditampilkan
                    localStorage.removeItem('successMessage');
                });
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            // Ganti ID form dengan ID yang sesuai
            @foreach ($admin as $adm)
            $('#editForm_{{ $adm->id }}').submit(function(e) {
                e.preventDefault();

                // Ambil semua inputan
                var nama_lengkap = $('#nama_{{ $adm->id }}').val();
                var nip = $('#nip_{{ $adm->id }}').val();
                var jabatan = $('#jabatan_{{ $adm->id }}').val();
                var foto = $('#foto_{{ $adm->id }}').val();
                var no_hp = $('#no_hp_{{ $adm->id }}').val();
                var email = $('#email_{{ $adm->id }}').val();
                var username = $('#username_{{ $adm->id }}').val();
                var password = $('#password_{{ $adm->id }}').val();

                // Cek jika inputan ada yang kosong
                if (!nama_lengkap || !nip || !jabatan || !no_hp || !email || !username) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Semua inputan harus diisi!',
                    });
                    return false;
                }

                // Validasi file pas foto (hanya jpg, jpeg, png)
                var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
                if (foto && !allowedExtensions.exec(foto)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Pas Foto harus berformat jpg, jpeg, atau png!',
                    });
                    return false;
                }

                // Konfirmasi untuk menyimpan data
                Swal.fire({
                    title: 'Update Data?',
                    text: "Apakah Anda yakin ingin memperbarui data ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Perbarui!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Tutup modal sebelum submit form
                        $('#basicModal{{ $adm->id }}').modal('hide');

                        // Submit form secara manual
                        $.ajax({
                            url: $(this).attr('action'), // Ambil URL action dari form
                            type: 'POST',
                            data: new FormData(
                            this), // Menggunakan FormData untuk file upload
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                // Menyimpan pesan sukses di local storage
                                localStorage.setItem('successMessage',
                                    'Data telah berhasil diperbarui!');

                                // Reload halaman
                                location.reload();
                            },
                            error: function(xhr) {
                                // Mengambil pesan error dari respons server
                                let errorMessage =
                                    'Terjadi kesalahan saat memperbarui data.';

                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON
                                    .message; // Mengambil pesan spesifik
                                }

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: errorMessage,
                                });
                            }
                        });
                    }
                });
            });

            // Menampilkan alert setelah halaman dimuat
            if (localStorage.getItem('successMessage')) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: localStorage.getItem('successMessage'),
                }).then(() => {
                    // Menghapus pesan dari local storage setelah ditampilkan
                    localStorage.removeItem('successMessage');
                });
            }
            @endforeach
        });
    </script>


    <script>
        window.deleteConfirm = function(e) {
            e.preventDefault(); // Mencegah pengiriman form

            var form = $(e.target).closest('form'); // Mengambil form terkait dan membungkusnya dengan jQuery

            Swal.fire({
                title: "Apakah Kamu yakin ?",
                text: "Akun ini akan kamu hapus!",
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
                                text: 'Akun Telah Berhasil dihapus.',
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

</body>

</html>
