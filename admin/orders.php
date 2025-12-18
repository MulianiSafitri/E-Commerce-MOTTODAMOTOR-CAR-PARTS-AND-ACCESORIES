<?php
// admin/orders.php
require_once '../inc/admin_auth.php';
verify_admin();

// Handle Status Update
if (isset($_POST['update_status'])) {
    $order_id = (int) $_POST['order_id'];
    $status = clean_input($_POST['status']);
    mysqli_query($conn, "UPDATE transactions SET status='$status' WHERE id=$order_id");
    header("Location: orders.php");
}

// Handle Delete Order
if (isset($_POST['delete_order'])) {
    $order_id = (int) $_POST['order_id'];
    
    // Delete transaction items first
    mysqli_query($conn, "DELETE FROM transaction_items WHERE transaction_id = $order_id");
    
    // Delete transaction
    mysqli_query($conn, "DELETE FROM transactions WHERE id = $order_id");
    
    header("Location: orders.php");
    exit;
}

$query = "SELECT t.*, u.nama_lengkap FROM transactions t JOIN users u ON t.user_id = u.id ORDER BY t.tanggal DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Manage Orders - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar Manual Copy -->
        <div class="bg-dark text-white p-3 vh-100 position-fixed" style="width: 250px; z-index:1000;">
            <h4 class="text-danger fw-bold mb-4">MOTTODA ADMIN</h4>
            <ul class="nav flex-column">
                <li class="nav-item mb-2"><a href="index.php" class="nav-link text-white"><i
                            class="fas fa-tachometer-alt me-2"></i> Dashboard</a></li>
                <li class="nav-item mb-2"><a href="products.php" class="nav-link text-white"><i
                            class="fas fa-box me-2"></i> Produk</a></li>
                <li class="nav-item mb-2"><a href="categories.php" class="nav-link text-white"><i
                            class="fas fa-tags me-2"></i> Kategori</a></li>
                <li class="nav-item mb-2"><a href="orders.php" class="nav-link text-white active bg-danger rounded"><i
                            class="fas fa-shopping-cart me-2"></i> Pesanan</a></li>
                <li class="nav-item mb-2"><a href="ongkir.php" class="nav-link text-white"><i
                            class="fas fa-truck me-2"></i> Ongkir</a></li>
                <li class="nav-item mb-2"><a href="reports.php" class="nav-link text-white"><i
                            class="fas fa-file-pdf me-2"></i> Laporan</a></li>
                <li class="nav-item mt-4"><a href="../inc/admin_auth.php?logout=true" class="nav-link text-danger"><i
                            class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
            </ul>
        </div>

        <div class="flex-grow-1 p-4" style="margin-left: 250px;">
            <h2 class="mb-4">Daftar Pesanan Masuk</h2>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Invoice</th>
                                    <th>Customer</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td class="fw-bold">#<?= $row['invoice_no'] ?></td>
                                        <td><?= $row['nama_lengkap'] ?></td>
                                        <td><?= date('d/m/y H:i', strtotime($row['tanggal'])) ?></td>
                                        <td><?= format_rupiah($row['total_bayar']) ?></td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <form action="" method="POST" class="d-flex">
                                                <input type="hidden" name="update_status" value="1">
                                                <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                                                <select name="status" class="form-select form-select-sm me-2"
                                                    onchange="this.form.submit()">
                                                    <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                                    <option value="Dibayar" <?= $row['status'] == 'Dibayar' ? 'selected' : '' ?>>Dibayar</option>
                                                    <option value="Dikirim" <?= $row['status'] == 'Dikirim' ? 'selected' : '' ?>>Dikirim</option>
                                                    <option value="Selesai" <?= $row['status'] == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                                                    <option value="Batal" <?= $row['status'] == 'Batal' ? 'selected' : '' ?>>
                                                        Batal</option>
                                                </select>
                                                </select>
                                                </form>
                                                
                                                <form action="" method="POST" onsubmit="return confirm('Yakin ingin menghapus pesanan ini?');">
                                                    <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                                                    <button type="submit" name="delete_order" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-dark" data-bs-toggle="modal"
                                                data-bs-target="#orderModal<?= $row['id'] ?>"><i
                                                    class="fas fa-eye"></i></button>

                                            <!-- Modal Detail Order -->
                                            <div class="modal fade" id="orderModal<?= $row['id'] ?>" tabindex="-1">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Detail #<?= $row['invoice_no'] ?></h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>
                                                                <strong>Metode Bayar:</strong>
                                                                <?= $row['metode_bayar'] ?><br>
                                                                <strong>Alamat:</strong> <?= $row['alamat_pengiriman'] ?>
                                                            </p>
                                                            <table class="table table-bordered">
                                                                <?php
                                                                $tid = $row['id'];
                                                                $items = mysqli_query($conn, "SELECT ti.*, p.nama_produk FROM transaction_items ti JOIN products p ON ti.product_id = p.id WHERE transaction_id = $tid");
                                                                while ($item = mysqli_fetch_assoc($items)):
                                                                    ?>
                                                                    <tr>
                                                                        <td><?= $item['nama_produk'] ?></td>
                                                                        <td>x<?= $item['qty'] ?></td>
                                                                        <td class="text-end">
                                                                            <?= format_rupiah($item['subtotal']) ?>
                                                                        </td>
                                                                    </tr>
                                                                <?php endwhile; ?>
                                                                <tr>
                                                                    <td colspan="2" class="text-end">Ongkir</td>
                                                                    <td class="text-end">
                                                                        <?= format_rupiah($row['total_ongkir']) ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2" class="text-end fw-bold">Grand Total
                                                                    </td>
                                                                    <td class="text-end fw-bold text-danger">
                                                                        <?= format_rupiah($row['total_bayar']) ?>
                                                                    </td>
                                                                </tr>
                                                            </table>
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
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>