<?php
// pages/cart.php
require_once '../inc/header.php';

$cart_details = get_cart_details();
$items = $cart_details['items'];
$total = $cart_details['total'];
?>

<div class="container py-5">
    <h2 class="fw-bold mb-4">Keranjang Belanja</h2>

    <?php if (empty($items)): ?>
        <div class="alert alert-info py-5 text-center">
            <i class="fas fa-shopping-basket fa-4x mb-3 text-muted"></i>
            <h4>Keranjang Anda masih kosong</h4>
            <p>Yuk cari sparepart untuk mobil kesayangan Anda!</p>
            <a href="katalog.php" class="btn btn-primary-mottoda mt-3">Belanja Sekarang</a>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <table class="table table-borderless align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Produk</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <tr class="border-bottom">
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <img src="<?= base_url('assets/img/products/' . ($item['gambar_utama'] ?: 'default.jpg')) ?>"
                                                    alt="Img" class="rounded me-3"
                                                    style="width: 60px; height: 60px; object-fit: cover;">
                                                <div>
                                                    <a href="product.php?slug=<?= $item['slug'] ?>"
                                                        class="text-decoration-none text-dark fw-bold">
                                                        <?= $item['nama_produk'] ?>
                                                    </a>
                                                    <div class="text-muted small"><?= $item['merek'] ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= format_rupiah($item['harga']) ?></td>
                                        <td style="width: 120px;">
                                            <form action="<?= base_url('inc/handle_cart.php') ?>" method="POST" class="d-flex">
                                                <input type="hidden" name="action" value="update">
                                                <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                                <input type="number" name="qty" value="<?= $item['qty'] ?>"
                                                    class="form-control form-control-sm me-2" min="1"
                                                    onchange="this.form.submit()">
                                            </form>
                                        </td>
                                        <td class="fw-bold text-danger"><?= format_rupiah($item['subtotal']) ?></td>
                                        <td>
                                            <form action="<?= base_url('inc/handle_cart.php') ?>" method="POST">
                                                <input type="hidden" name="action" value="remove">
                                                <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                                <button type="submit" class="btn btn-link text-danger p-0"
                                                    onclick="return confirm('Hapus item ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Ringkasan Belanja</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Harga (<?= count($items) ?> barang)</span>
                            <span class="fw-bold"><?= format_rupiah($total) ?></span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold fs-5">Total</span>
                            <span class="fw-bold fs-5 text-danger"><?= format_rupiah($total) ?></span>
                        </div>
                        <div class="d-grid">
                            <a href="checkout.php" class="btn btn-primary-mottoda py-2">Mulai Checkout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../inc/footer.php'; ?>