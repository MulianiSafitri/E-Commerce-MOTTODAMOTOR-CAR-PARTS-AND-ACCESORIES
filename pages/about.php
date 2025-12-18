<?php
// pages/about.php
require_once __DIR__ . '/../inc/header.php';
?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-6 mb-4 mb-md-0">
            <img src="<?= base_url('assets/img/1.png') ?>" class="img-fluid rounded shadow" alt="About Us">
        </div>
        <div class="col-md-6">
            <h1 class="mb-4">About Us</h1>
            <div class="card shadow-sm">
                <div class="card-body">
                    <p class="lead">MOTTODA MOTOR adalah penyedia sparepart mobil terlengkap dan terpercaya.</p>
                    <p>Kami menyediakan berbagai macam suku cadang berkualitas untuk berbagai jenis mobil. Dengan
                        pengalaman bertahun-tahun, kami berkomitmen untuk memberikan pelayanan terbaik bagi pelanggan
                        kami.</p>
                    <p>Visi kami adalah menjadi toko sparepart mobil nomor satu di Indonesia yang mengutamakan kepuasan
                        pelanggan dan kualitas produk.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../inc/footer.php'; ?>