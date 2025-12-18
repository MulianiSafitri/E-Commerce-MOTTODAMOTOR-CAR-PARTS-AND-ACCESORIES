<?php
// admin/products.php
require_once '../inc/admin_auth.php';
verify_admin();

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    mysqli_query($conn, "DELETE FROM products WHERE id=$id");
    header("Location: products.php");
}

$result = mysqli_query($conn, "SELECT p.*, c.nama_kategori FROM products p JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC");
?>
<!DOCTYPE html>
<html>

<head>
    <title>Manage Products - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="d-flex">
        <?php include 'sidebar.php'; // Simplified include if sidebar.php exists, else manual below ?>
        <!-- Sidebar is now included via sidebar.php -->

        <div class="flex-grow-1 p-4" style="margin-left: 250px;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Manajemen Produk Sparepart</h2>
                <a href="product_add.php" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Produk</a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Gambar</th>
                                    <th>Nama Produk</th>
                                    <th>Kategori</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td>
                                            <img src="../assets/img/products/<?= $row['gambar_utama'] ?: 'default.jpg' ?>"
                                                width="50">
                                        </td>
                                        <td><?= $row['nama_produk'] ?></td>
                                        <td><?= $row['nama_kategori'] ?></td>
                                        <td><?= format_rupiah($row['harga']) ?></td>
                                        <td><?= $row['stok'] ?></td>
                                        <td>
                                            <a href="product_edit.php?id=<?= $row['id'] ?>"
                                                class="btn btn-sm btn-info text-white"><i class="fas fa-edit"></i></a>
                                            <a href="products.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Hapus produk ini?')"><i
                                                    class="fas fa-trash"></i></a>
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
</body>

</html>