<?php
// admin/categories.php
require_once '../inc/admin_auth.php';
verify_admin();

// Handle Add/Edit/Delete
if (isset($_POST['save'])) {
    $nama = clean_input($_POST['nama_kategori']);
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $nama)));

    if (!empty($_POST['id'])) {
        $id = (int) $_POST['id'];
        mysqli_query($conn, "UPDATE categories SET nama_kategori='$nama', slug='$slug' WHERE id=$id");
    } else {
        mysqli_query($conn, "INSERT INTO categories (nama_kategori, slug) VALUES ('$nama', '$slug')");
    }
    header("Location: categories.php");
}

if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    mysqli_query($conn, "DELETE FROM categories WHERE id=$id");
    header("Location: categories.php");
}

$edit_data = null;
if (isset($_GET['edit'])) {
    $id = (int) $_GET['edit'];
    $edit_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM categories WHERE id=$id"));
}

$result = mysqli_query($conn, "SELECT * FROM categories ORDER BY nama_kategori ASC");
?>
<!DOCTYPE html>
<html>

<head>
    <title>Manage Categories - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar Manual -->
        <div class="bg-dark text-white p-3 vh-100 position-fixed" style="width: 250px;">
            <h4 class="text-danger fw-bold mb-4">MOTTODA ADMIN</h4>
            <ul class="nav flex-column">
                <li class="nav-item mb-2"><a href="index.php" class="nav-link text-white"><i
                            class="fas fa-tachometer-alt me-2"></i> Dashboard</a></li>
                <li class="nav-item mb-2"><a href="products.php" class="nav-link text-white"><i
                            class="fas fa-box me-2"></i> Produk</a></li>
                <li class="nav-item mb-2"><a href="categories.php"
                        class="nav-link text-white active bg-danger rounded"><i class="fas fa-tags me-2"></i>
                        Kategori</a></li>
                <li class="nav-item mb-2"><a href="orders.php" class="nav-link text-white"><i
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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Manajemen Kategori</h2>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white fw-bold">
                            <?= $edit_data ? 'Edit Kategori' : 'Tambah Kategori' ?>
                        </div>
                        <div class="card-body">
                            <form action="" method="POST">
                                <?php if ($edit_data): ?>
                                    <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
                                <?php endif; ?>
                                <div class="mb-3">
                                    <label>Nama Kategori</label>
                                    <input type="text" name="nama_kategori" class="form-control"
                                        value="<?= $edit_data['nama_kategori'] ?? '' ?>" required>
                                </div>
                                <div class="d-grid gap-2">
                                    <button type="submit" name="save"
                                        class="btn btn-primary"><?= $edit_data ? 'Update' : 'Simpan' ?></button>
                                    <?php if ($edit_data): ?>
                                        <a href="categories.php" class="btn btn-secondary">Batal</a>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama</th>
                                        <th>Slug</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                            <td><?= $row['id'] ?></td>
                                            <td><?= $row['nama_kategori'] ?></td>
                                            <td><?= $row['slug'] ?></td>
                                            <td>
                                                <a href="categories.php?edit=<?= $row['id'] ?>"
                                                    class="btn btn-sm btn-info text-white"><i class="fas fa-edit"></i></a>
                                                <a href="categories.php?delete=<?= $row['id'] ?>"
                                                    class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')"><i
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
    </div>
</body>

</html>