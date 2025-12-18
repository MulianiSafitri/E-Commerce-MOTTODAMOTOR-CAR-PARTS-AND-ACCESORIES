<?php
// pages/product.php
require_once '../inc/header.php';

$slug = isset($_GET['slug']) ? clean_input($_GET['slug']) : '';
$product = get_product_by_slug($slug);

if (!$product) {
    echo "<div class='container py-5 text-center'><h3>Produk tidak ditemukan</h3><a href='katalog.php' class='btn btn-primary'>Kembali ke Katalog</a></div>";
    require_once '../inc/footer.php';
    exit;
}

// Ambil Rekomendasi (Content Based Filtering)
$recommendations = get_cosine_similarity($product['id']);
?>

<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('index.php') ?>">Home</a></li>
            <li class="breadcrumb-item"><a
                    href="katalog.php?category_slug=<?= strtolower($product['nama_kategori']) ?>"><?= $product['nama_kategori'] ?></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page"><?= $product['nama_produk'] ?></li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Images -->
        <div class="col-md-5 mb-4">
            <div class="card border-0 shadow-sm">
                <img src="<?= base_url('assets/img/products/' . ($product['gambar_utama'] ?: 'default.jpg')) ?>"
                    class="card-img-top img-fluid" alt="<?= $product['nama_produk'] ?>"
                    onerror="this.onerror=null; this.src='<?= base_url('assets/img/no-image.png') ?>'">
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-md-7">
            <h2 class="fw-bold mb-2"><?= $product['nama_produk'] ?></h2>
            <div class="mb-3">
                <span class="badge bg-secondary cursor-pointer me-1"><?= $product['nama_kategori'] ?></span>
                <span class="badge bg-danger me-1"><?= $product['kondisi'] ?></span>
                <span class="badge bg-dark"><?= $product['merek'] ?></span>
            </div>

            <h3 class="product-price text-danger mb-3"><?= format_rupiah($product['harga']) ?></h3>

            <div class="p-3 bg-light rounded mb-4">
                <div class="row">
                    <div class="col-6">
                        <strong>Tipe Mobil:</strong><br>
                        <?= $product['tipe_mobil'] ?: '-' ?>
                    </div>
                    <div class="col-6">
                        <strong>Berat:</strong><br>
                        <?= $product['berat'] ?> gram
                    </div>
                    <div class="col-6 mt-3">
                        <strong>Stok:</strong><br>
                        <?= $product['stok'] > 0 ? $product['stok'] . ' unit' : '<span class="text-danger">Habis</span>' ?>
                    </div>
                    <div class="col-6 mt-3">
                        <strong>Terjual:</strong><br>
                        <?= $product['terjual'] ?> pcs
                    </div>
                </div>
            </div>

            <p class="lead" style="font-size: 1rem; text-align: justify;">
                <?= nl2br($product['deskripsi']) ?>
            </p>

            <hr>

            <form action="<?= base_url('inc/handle_cart.php') ?>" method="POST" class="row align-items-end g-2">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <input type="hidden" name="action" value="add">

                <div class="col-auto">
                    <label class="form-label fw-bold">Jumlah</label>
                    <input type="number" class="form-control" name="qty" value="1" min="1" max="<?= $product['stok'] ?>"
                        style="width: 80px;">
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary-mottoda w-100 py-2" <?= $product['stok'] <= 0 ? 'disabled' : '' ?>>
                        <i class="fas fa-shopping-cart me-2"></i> Tambah ke Keranjang
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Recommendations -->
    <?php if (!empty($recommendations)): ?>
        <div class="mt-5">
            <h3 class="fw-bold border-bottom pb-2 mb-4">Produk <span class="text-danger">Terkait</span> (Rekomendasi)</h3>
            <div class="row g-3">
                <?php foreach ($recommendations as $rec): ?>
                    <div class="col-6 col-md-3 col-lg-2">
                        <div class="card card-product h-100 text-center p-2">
                            <img src="<?= base_url('assets/img/products/' . ($rec['gambar_utama'] ?: 'default.jpg')) ?>"
                                class="card-img-top mb-2 rounded" style="height: 120px; object-fit: cover;"
                                alt="<?= $rec['nama_produk'] ?>"
                                onerror="this.onerror=null; this.src='<?= base_url('assets/img/no-image.png') ?>'">
                            <h6 class="text-truncate" style="font-size: 0.9rem;">
                                <a href="product.php?slug=<?= $rec['slug'] ?>"
                                    class="text-decoration-none text-dark"><?= $rec['nama_produk'] ?></a>
                            </h6>
                            <div class="fw-bold text-danger smaller"><?= format_rupiah($rec['harga']) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../inc/footer.php'; ?>