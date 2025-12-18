<?php
// pages/katalog.php
require_once '../inc/header.php';

// Prepare filters
$filters = [
    'category_slug' => $_GET['category_slug'] ?? '',
    'min_price' => $_GET['min_price'] ?? '',
    'max_price' => $_GET['max_price'] ?? '',
    'sort' => $_GET['sort'] ?? '',
    'search' => $_GET['search'] ?? '',
    'brand' => $_GET['brand'] ?? '',
    'car_type' => $_GET['car_type'] ?? ''
];

// Logic Search vs Regular Filter
if (!empty($filters['search'])) {
    // Kalau ada search, pakai TF-IDF function
    $products_data = search_products($filters['search']);
    $is_search = true;
} else {
    // Kalau normal browsing
    $products_query = get_products($filters);
    $products_data = [];
    while ($row = mysqli_fetch_assoc($products_query)) {
        $products_data[] = $row;
    }
    $is_search = false;
}

$categories = get_categories();
?>

<div class="container py-5">
    <div class="row">
        <!-- Sidebar Filter -->
        <div class="col-lg-3 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom-0 pt-3">
                    <h5 class="fw-bold mb-0">Filter Produk</h5>
                </div>
                <div class="card-body">
                    <form action="" method="GET">
                        <?php if (!empty($filters['search'])): ?>
                            <input type="hidden" name="search" value="<?= htmlspecialchars($filters['search']) ?>">
                        <?php endif; ?>

                        <!-- Kategori -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kategori</label>
                            <select class="form-select" name="category_slug">
                                <option value="">Semua Kategori</option>
                                <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                                    <option value="<?= $cat['slug'] ?>" <?= $filters['category_slug'] == $cat['slug'] ? 'selected' : '' ?>>
                                        <?= $cat['nama_kategori'] ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <!-- Harga -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Harga</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text">Min</span>
                                <input type="number" class="form-control" name="min_price"
                                    value="<?= $filters['min_price'] ?>">
                            </div>
                            <div class="input-group">
                                <span class="input-group-text">Max</span>
                                <input type="number" class="form-control" name="max_price"
                                    value="<?= $filters['max_price'] ?>">
                            </div>
                        </div>

                        <!-- Brand -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Merek / Brand</label>
                            <input type="text" class="form-control" name="brand" placeholder="Toyota, Honda..."
                                value="<?= htmlspecialchars($filters['brand']) ?>">
                        </div>

                        <!-- Tipe Mobil -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tipe Mobil</label>
                            <input type="text" class="form-control" name="car_type" placeholder="Avanza, Jazz..."
                                value="<?= htmlspecialchars($filters['car_type']) ?>">
                        </div>

                        <!-- Sort -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Urutkan</label>
                            <select class="form-select" name="sort">
                                <option value="newest" <?= $filters['sort'] == 'newest' ? 'selected' : '' ?>>Terbaru
                                </option>
                                <option value="price_asc" <?= $filters['sort'] == 'price_asc' ? 'selected' : '' ?>>Harga
                                    Terendah</option>
                                <option value="price_desc" <?= $filters['sort'] == 'price_desc' ? 'selected' : '' ?>>Harga
                                    Tertinggi</option>
                            </select>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary-mottoda">Terapkan Filter</button>
                            <a href="katalog.php" class="btn btn-outline-secondary mt-2">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Product List -->
        <div class="col-lg-9">
            <?php if ($is_search): ?>
                <div class="alert alert-info">
                    Menampilkan hasil pencarian untuk: <strong>"<?= htmlspecialchars($filters['search']) ?>"</strong>
                </div>
            <?php endif; ?>

            <?php if (empty($products_data)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4>Produk tidak ditemukan</h4>
                    <p>Coba kata kunci lain atau kurangi filter.</p>
                </div>
            <?php else: ?>
                <div class="row g-3">
                    <?php foreach ($products_data as $prod): ?>
                        <div class="col-6 col-md-4">
                            <div class="card card-product h-100">
                                <img src="<?= base_url('assets/img/products/' . ($prod['gambar_utama'] ?: 'default.jpg')) ?>"
                                    class="card-img-top" alt="<?= $prod['nama_produk'] ?>"
                                    onerror="this.onerror=null; this.src='<?= base_url('assets/img/no-image.png') ?>'">
                                <div class="card-body d-flex flex-column">
                                    <small class="text-muted"><?= $prod['merek'] ?? 'Mottoda' ?></small>
                                    <h5 class="card-title text-truncate" style="font-size: 1rem;">
                                        <a href="<?= base_url('pages/product.php?slug=' . $prod['slug']) ?>"
                                            class="text-decoration-none text-dark">
                                            <?= $prod['nama_produk'] ?>
                                        </a>
                                    </h5>
                                    <p class="card-text small text-secondary mb-1"><?= $prod['tipe_mobil'] ?></p>
                                    <h5 class="product-price mb-2"><?= format_rupiah($prod['harga']) ?></h5>

                                    <div class="mt-auto">
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
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once '../inc/footer.php'; ?>