<?php
// pages/orders.php
require_once '../inc/header.php';
check_login();

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM transactions WHERE user_id = $user_id ORDER BY tanggal DESC";
$result = mysqli_query($conn, $query);
?>

<div class="container py-5">
    <h3 class="fw-bold mb-4">Riwayat Pesanan</h3>
    <?= flash_msg('order_success') ?>

    <?php if (mysqli_num_rows($result) == 0): ?>
        <p class="text-muted">Belum ada pesanan.</p>
    <?php else: ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>Invoice</th>
                                <th>Tanggal</th>
                                <th>Total Bayar</th>
                                <th>Metode Bayar</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td class="fw-bold text-primary">#<?= $row['invoice_no'] ?></td>
                                    <td><?= date('d M Y H:i', strtotime($row['tanggal'])) ?></td>
                                    <td class="fw-bold"><?= format_rupiah($row['total_bayar']) ?></td>
                                    <td><?= $row['metode_bayar'] ?></td>
                                    <td>
                                        <?php
                                        $badges = [
                                            'Pending' => 'bg-warning',
                                            'Dibayar' => 'bg-info',
                                            'Dikirim' => 'bg-primary',
                                            'Selesai' => 'bg-success',
                                            'Batal' => 'bg-danger'
                                        ];
                                        $bg = $badges[$row['status']] ?? 'bg-secondary';
                                        ?>
                                        <span class="badge <?= $bg ?>"><?= $row['status'] ?></span>
                                    </td>
                                    <td>
                                        <!-- Detail Modal Trigger or Link -->
                                        <a href="<?= base_url('pages/track_order.php?invoice=' . $row['invoice_no']) ?>"
                                            class="btn btn-sm btn-danger me-1">Lacak</a>
                                        <button class="btn btn-sm btn-outline-dark" data-bs-toggle="modal"
                                            data-bs-target="#modal<?= $row['id'] ?>">Detail</button>

                                        <!-- Simple Modal for Detail -->
                                        <div class="modal fade" id="modal<?= $row['id'] ?>" tabindex="-1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Detail Pesanan #<?= $row['invoice_no'] ?></h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>
                                                            <strong>Alamat:</strong> <?= $row['alamat_pengiriman'] ?><br>
                                                            <strong>Kurir:</strong> <?= $row['kurir'] ?>
                                                        </p>
                                                        <table class="table table-sm table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>Produk</th>
                                                                    <th>Qty</th>
                                                                    <th>Subtotal</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $tid = $row['id'];
                                                                $items_q = mysqli_query($conn, "SELECT ti.*, p.nama_produk FROM transaction_items ti JOIN products p ON ti.product_id = p.id WHERE transaction_id = $tid");
                                                                while ($item = mysqli_fetch_assoc($items_q)):
                                                                    ?>
                                                                    <tr>
                                                                        <td><?= $item['nama_produk'] ?></td>
                                                                        <td><?= $item['qty'] ?></td>
                                                                        <td><?= format_rupiah($item['subtotal']) ?></td>
                                                                    </tr>
                                                                <?php endwhile; ?>
                                                            </tbody>
                                                        </table>
                                                        <div class="d-flex justify-content-between fw-bold">
                                                            <span>Ongkir</span>
                                                            <span><?= format_rupiah($row['total_ongkir']) ?></span>
                                                        </div>
                                                        <div class="d-flex justify-content-between fw-bold text-danger">
                                                            <span>Total</span>
                                                            <span><?= format_rupiah($row['total_bayar']) ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <?php if ($row['status'] == 'Pending' && $row['metode_bayar'] != 'COD'): ?>
                                                            <a href="#" class="btn btn-primary"
                                                                onclick="alert('Fitur Upload Bukti Bayar belum diimplementasi di demo ini.')">Bayar
                                                                Sekarang</a>
                                                        <?php endif; ?>
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Tutup</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../inc/footer.php'; ?>