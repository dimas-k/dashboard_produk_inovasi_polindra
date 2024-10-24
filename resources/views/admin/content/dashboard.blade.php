<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <!-- Kartu 1 -->
        <div class="col-lg-3 col-md-12 col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <p class="mb-2 fw-bold">Produk Yang Sudah Tervalidasi</p>
                    <h2 class="card-title mb-1">{{ $prdk_valid }}</h2>
                </div>
            </div>
        </div>

        <!-- Kartu 2 -->
        <div class="col-lg-3 col-md-12 col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <p class="mb-2 fw-bold">Produk Yang Belum Divalidasi</p>
                    <h2 class="card-title mb-1">{{ $prdk_nonvalid }}</h2>
                </div>
            </div>
        </div>

        <!-- Kartu 3 -->
        <div class="col-lg-3 col-md-12 col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <p class="mb-1 fw-bold">Penelitian Yang Sudah Tervalidasi</p>
                    <h2 class="card-title">{{ $pnltan_valid }}</h2>
                </div>
            </div>
        </div>

        <!-- Kartu 4 -->
        <div class="col-lg-3 col-md-12 col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <p class="mb-2 fw-bold">Penelitian Yang Belum Divalidasi</p>
                    <h2 class="card-title mb-1">{{ $pnltan_nonvalid }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>
