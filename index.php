<?php
// index.php
require_once 'inc/header.php';

// Ambil Produk Terbaru
$latest_products = get_products(['sort' => 'newest']); // Limit handle in loop or SQL if needed
// Ambil Kategori
$categories = get_categories();
?>

<!-- Hero Section -->
<section class="hero-section d-flex align-items-center justify-content-center flex-column">
    <div class="container text-center">
        <h1 class="display-3 fw-bold mb-3">Solusi Sparepart Mobil Terpercaya</h1>
        <p class="lead mb-4">Temukan suku cadang berkualitas untuk Toyota, Honda, Daihatsu, dan lainnya.</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="<?= base_url('pages/katalog.php') ?>" class="btn btn-primary-mottoda btn-lg px-5">Belanja
                Sekarang</a>
            <a href="#kategori" class="btn btn-outline-dark btn-lg px-5">Lihat Kategori</a>
        </div>
    </div>
</section>

<!-- Kategori Section -->
<section id="kategori" class="py-5 bg-white">
    <div class="container">
        <h2 class="text-center fw-bold mb-5"><span class="text-danger">Kategori</span> Pilihan</h2>
        <div class="row g-4 justify-content-center">
            <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                <div class="col-6 col-md-3 col-lg-2">
                    <a href="<?= base_url('pages/katalog.php?category_slug=' . $cat['slug']) ?>"
                        class="text-decoration-none text-dark">
                        <div class="card h-100 border-0 shadow-sm text-center p-3 hover-scale">
                            <div class="mb-3">
                                <i class="fas fa-car-battery fa-3x text-danger"></i> <!-- Placeholder Icon -->
                            </div>
                            <h6 class="fw-bold"><?= $cat['nama_kategori'] ?></h6>
                        </div>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- Produk Terbaru Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Produk <span class="text-danger">Terbaru</span></h2>
            <a href="<?= base_url('pages/katalog.php') ?>" class="btn btn-outline-danger">Lihat Semua</a>
        </div>

        <div class="row g-4">
            <?php
            $count = 0;
            while ($prod = mysqli_fetch_assoc($latest_products)):
                if ($count >= 8)
                    break; // Limit 8 items
                ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card card-product h-100 position-relative">
                        <span class="category-badge"><?= $prod['nama_kategori'] ?></span>
                        <!-- Placeholder Image jika tidak ada gambar -->
                        <img src="<?= base_url('assets/img/products/' . ($prod['gambar_utama'] ?: 'default.jpg')) ?>"
                            class="card-img-top" alt="<?= $prod['nama_produk'] ?>"
                            onerror="this.onerror=null; this.src='<?= base_url('assets/img/no-image.png') ?>'">

                        <div class="card-body d-flex flex-column">
                            <small class="text-muted mb-1"><?= $prod['merek'] ?></small>
                            <h5 class="card-title text-truncate" style="font-size: 1rem;"><?= $prod['nama_produk'] ?></h5>
                            <p class="card-text text-truncate small text-secondary"><?= $prod['tipe_mobil'] ?></p>

                            <div class="mt-auto">
                                <h5 class="product-price mb-3"><?= format_rupiah($prod['harga']) ?></h5>
                                <div class="d-grid gap-2">
                                    <a href="<?= base_url('pages/product.php?slug=' . $prod['slug']) ?>"
                                        class="btn btn-outline-mottoda btn-sm">Detail</a>
                                    <form action="<?= base_url('inc/handle_cart.php') ?>" method="POST" class="d-grid">
                                        <input type="hidden" name="product_id" value="<?= $prod['id'] ?>">
                                        <input type="hidden" name="action" value="add">
                                        <button type="submit" class="btn btn-primary-mottoda btn-sm">
                                            <i class="fas fa-shopping-cart"></i> +Keranjang
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                $count++;
            endwhile;
            ?>
        </div>
    </div>
</section>

<!-- Features Info -->
<section class="py-5 bg-white text-dark">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-md-4">
                <i class="fas fa-truck fa-3x mb-3 text-danger"></i>
                <h4>Pengiriman Cepat</h4>
                <p class="text-muted">Dukungan berbagai ekspedisi JNE, J&T, SiCepat ke seluruh Indonesia.</p>
            </div>
            <div class="col-md-4">
                <i class="fas fa-shield-alt fa-3x mb-3 text-danger"></i>
                <h4>Jaminan Original</h4>
                <p class="text-muted">Barang dijamin asli dan berkualitas. Garansi uang kembali.</p>
            </div>
            <div class="col-md-4">
                <i class="fas fa-headset fa-3x mb-3 text-danger"></i>
                <h4>Layanan 24/7</h4>
                <p class="text-muted">Tim support kami siap membantu anda mencari sparepart yang tepat.</p>
            </div>
        </div>
    </div>
</section>

<?php require_once 'inc/footer.php'; ?>