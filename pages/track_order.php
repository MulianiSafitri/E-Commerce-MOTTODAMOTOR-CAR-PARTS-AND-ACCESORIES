<?php
// pages/track_order.php
require_once '../inc/header.php';

$invoice = isset($_GET['invoice']) ? clean_input($_GET['invoice']) : '';
$order = null;
$error = '';

if ($invoice) {
    // Cari order berdasarkan Invoice Number (assuming exact match or from DB)
    // Karena di DB invoice_no disimpan tanpa prefix '#', kita harus pastikan inputnya bersih
    $invoice_clean = str_replace('#', '', $invoice);

    // Perlu join ke user untuk validasi pemilikan order (opsional, tapi bagus untuk privasi)
    // Di sini kita biarkan public untuk "Guest Tracking" atau cek milik sendiri jika login

    $query = "SELECT * FROM transactions WHERE invoice_no = '$invoice_clean'";

    // Jika user login, bisa kita restrict cuma punya dia, tapi "Lacak Pengiriman" biasanya public features dengan input invoice
    // Tapi untuk keamanan data, sebaiknya minimal tau invoice nya.

    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $order = mysqli_fetch_assoc($result);
    } else {
        $error = "Pesanan dengan Invoice #$invoice_clean tidak ditemukan.";
    }
}
?>

<div class="container py-5" style="min-height: 60vh;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="text-center mb-5">
                <h2 class="fw-bold"><span class="text-danger">Lacak</span> Pengiriman</h2>
                <p class="text-muted">Masukkan nomor invoice Anda untuk melacak status pesanan.</p>
            </div>

            <!-- Search Form -->
            <div class="card shadow-sm mb-5 border-0">
                <div class="card-body p-4">
                    <form action="" method="GET" class="d-flex gap-2">
                        <input type="text" name="invoice" class="form-control form-control-lg"
                            placeholder="Contoh: INV12345" value="<?= htmlspecialchars($invoice) ?>" required>
                        <button type="submit" class="btn btn-danger btn-lg px-4">
                            <i class="fas fa-search me-2"></i>Cari
                        </button>
                    </form>
                    <?php if ($error): ?>
                        <div class="alert alert-danger mt-3 mb-0">
                            <i class="fas fa-exclamation-circle me-2"></i> <?= $error ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($order): ?>
                <!-- Order Status Result -->
                <div class="card shadow-lg border-0 overflow-hidden">
                    <div class="card-header bg-dark text-white p-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Status Pesanan: #<?= $order['invoice_no'] ?></h5>
                        <span class="badge bg-danger rounded-pill px-3"><?= $order['status'] ?></span>
                    </div>
                    <div class="card-body p-4">

                        <!-- Timeline -->
                        <div class="position-relative m-4">
                            <div class="progress" style="height: 2px;">
                                <?php
                                $status_progress = [
                                    'Pending' => 25,
                                    'Dibayar' => 50,
                                    'Dikirim' => 75,
                                    'Selesai' => 100,
                                    'Batal' => 0
                                ];
                                $width = $status_progress[$order['status']] ?? 0;

                                // Color logic
                                $bar_color = ($order['status'] == 'Batal') ? 'bg-danger' : 'bg-success';
                                ?>
                                <div class="progress-bar <?= $bar_color ?>" role="progressbar"
                                    style="width: <?= $width ?>%;"></div>
                            </div>

                            <?php if ($order['status'] != 'Batal'): ?>
                                <div class="d-flex justify-content-between position-absolute top-0 w-100 translate-middle-y">
                                    <!-- Step 1 -->
                                    <div class="text-center bg-white px-2">
                                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center mx-auto"
                                            style="width: 30px; height: 30px;">
                                            <i class="fas fa-clipboard-check small"></i>
                                        </div>
                                        <p class="small text-muted mt-2 mb-0">Pesanan<br>Dibuat</p>
                                    </div>
                                    <!-- Step 2 -->
                                    <div class="text-center bg-white px-2">
                                        <div class="rounded-circle <?= ($width >= 50) ? 'bg-success text-white' : 'bg-secondary text-light' ?> d-flex align-items-center justify-content-center mx-auto"
                                            style="width: 30px; height: 30px;">
                                            <i class="fas fa-money-bill-wave small"></i>
                                        </div>
                                        <p class="small text-muted mt-2 mb-0">Pembayaran<br>Diterima</p>
                                    </div>
                                    <!-- Step 3 -->
                                    <div class="text-center bg-white px-2">
                                        <div class="rounded-circle <?= ($width >= 75) ? 'bg-success text-white' : 'bg-secondary text-light' ?> d-flex align-items-center justify-content-center mx-auto"
                                            style="width: 30px; height: 30px;">
                                            <i class="fas fa-shipping-fast small"></i>
                                        </div>
                                        <p class="small text-muted mt-2 mb-0">Dalam<br>Pengiriman</p>
                                    </div>
                                    <!-- Step 4 -->
                                    <div class="text-center bg-white px-2">
                                        <div class="rounded-circle <?= ($width >= 100) ? 'bg-success text-white' : 'bg-secondary text-light' ?> d-flex align-items-center justify-content-center mx-auto"
                                            style="width: 30px; height: 30px;">
                                            <i class="fas fa-check small"></i>
                                        </div>
                                        <p class="small text-muted mt-2 mb-0">Pesanan<br>Selesai</p>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="text-center mt-4">
                                    <h4 class="text-danger"><i class="fas fa-times-circle"></i> Pesanan Dibatalkan</h4>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if ($width >= 75 && $order['status'] !== 'Batal'): ?>
                            <div class="alert alert-info mt-5 d-flex align-items-center">
                                <i class="fas fa-truck fa-2x me-3"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Info Pengiriman</h6>
                                    <p class="mb-0 small">Pesanan sedang dikirim menggunakan kurir
                                        <strong><?= $order['kurir'] ?></strong> ke alamat:</p>
                                    <p class="mb-0 fw-bold fst-italic">"<?= $order['alamat_pengiriman'] ?>"</p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <hr class="my-4">

                        <h6 class="fw-bold mb-3">Detail Produk</h6>
                        <ul class="list-group list-group-flush">
                            <?php
                            $tid = $order['id'];
                            $items = mysqli_query($conn, "SELECT ti.*, p.nama_produk FROM transaction_items ti JOIN products p ON ti.product_id = p.id WHERE transaction_id = $tid");
                            while ($item = mysqli_fetch_assoc($items)):
                                ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <span class="fw-bold"><?= $item['nama_produk'] ?></span>
                                        <div class="text-muted small">Qty: <?= $item['qty'] ?></div>
                                    </div>
                                    <span class="text-danger fw-bold"><?= format_rupiah($item['subtotal']) ?></span>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php require_once '../inc/footer.php'; ?>