<?php
// admin/ongkir.php
require_once '../inc/admin_auth.php';
verify_admin();

// Handle Add/Edit
if (isset($_POST['save'])) {
    $prov = clean_input($_POST['provinsi']);
    $kota = clean_input($_POST['kota']);
    $kec = clean_input($_POST['kecamatan']);
    $jne = clean_input($_POST['jne_reg']);
    $jnt = clean_input($_POST['jnt']);
    $sicepat = clean_input($_POST['sicepat']);
    $cod = clean_input($_POST['cod']);

    if (!empty($_POST['id'])) {
        $id = (int) $_POST['id'];
        $query = "UPDATE ongkir SET provinsi='$prov', kota='$kota', kecamatan='$kec', jne_reg='$jne', jnt='$jnt', sicepat='$sicepat', cod='$cod' WHERE id=$id";
    } else {
        $query = "INSERT INTO ongkir (provinsi, kota, kecamatan, jne_reg, jnt, sicepat, cod) VALUES ('$prov', '$kota', '$kec', '$jne', '$jnt', '$sicepat', '$cod')";
    }
    mysqli_query($conn, $query);
    header("Location: ongkir.php");
}

if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    mysqli_query($conn, "DELETE FROM ongkir WHERE id=$id");
    header("Location: ongkir.php");
}

$edit_data = null;
if (isset($_GET['edit'])) {
    $id = (int) $_GET['edit'];
    $edit_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM ongkir WHERE id=$id"));
}

$result = mysqli_query($conn, "SELECT * FROM ongkir ORDER BY provinsi, kota ASC");
?>
<!DOCTYPE html>
<html>

<head>
    <title>Manage Ongkir - Admin</title>
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
                <li class="nav-item mb-2"><a href="categories.php" class="nav-link text-white"><i
                            class="fas fa-tags me-2"></i> Kategori</a></li>
                <li class="nav-item mb-2"><a href="orders.php" class="nav-link text-white"><i
                            class="fas fa-shopping-cart me-2"></i> Pesanan</a></li>
                <li class="nav-item mb-2"><a href="ongkir.php" class="nav-link text-white active bg-danger rounded"><i
                            class="fas fa-truck me-2"></i> Ongkir</a></li>
                <li class="nav-item mb-2"><a href="reports.php" class="nav-link text-white"><i
                            class="fas fa-file-pdf me-2"></i> Laporan</a></li>
                <li class="nav-item mt-4"><a href="../inc/admin_auth.php?logout=true" class="nav-link text-danger"><i
                            class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
            </ul>
        </div>

        <div class="flex-grow-1 p-4" style="margin-left: 250px;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Manajemen Ongkos Kirim</h2>
                <button class="btn btn-primary" onclick="document.getElementById('formCard').scrollIntoView()">+
                    Tambah</button>
            </div>

            <!-- List Table -->
            <div class="card shadow-sm mb-5">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Provinsi</th>
                                    <th>Kota</th>
                                    <th>Kecamatan</th>
                                    <th>JNE</th>
                                    <th>JNT</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?= $row['provinsi'] ?></td>
                                        <td><?= $row['kota'] ?></td>
                                        <td><?= $row['kecamatan'] ?></td>
                                        <td><?= number_format($row['jne_reg']) ?></td>
                                        <td><?= number_format($row['jnt']) ?></td>
                                        <td>
                                            <a href="ongkir.php?edit=<?= $row['id'] ?>"
                                                class="btn btn-xs btn-info text-white"><i class="fas fa-edit"></i></a>
                                            <a href="ongkir.php?delete=<?= $row['id'] ?>" class="btn btn-xs btn-danger"
                                                onclick="return confirm('Hapus?')"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="card shadow-sm" id="formCard">
                <div class="card-header bg-white fw-bold">
                    <?= $edit_data ? 'Edit Ongkir' : 'Tambah Ongkir' ?>
                </div>
                <div class="card-body">
                    <form action="" method="POST" class="row g-3">
                        <?php if ($edit_data): ?>
                            <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
                        <?php endif; ?>

                        <div class="col-md-4">
                            <label class="form-label">Provinsi</label>
                            <input type="text" name="provinsi" class="form-control"
                                value="<?= $edit_data['provinsi'] ?? '' ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kota</label>
                            <input type="text" name="kota" class="form-control" value="<?= $edit_data['kota'] ?? '' ?>"
                                required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kecamatan</label>
                            <input type="text" name="kecamatan" class="form-control"
                                value="<?= $edit_data['kecamatan'] ?? '' ?>" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Tarif JNE REG</label>
                            <input type="number" name="jne_reg" class="form-control"
                                value="<?= $edit_data['jne_reg'] ?? '' ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tarif JNT EZ</label>
                            <input type="number" name="jnt" class="form-control" value="<?= $edit_data['jnt'] ?? '' ?>"
                                required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tarif SiCepat</label>
                            <input type="number" name="sicepat" class="form-control"
                                value="<?= $edit_data['sicepat'] ?? '' ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tarif COD</label>
                            <input type="number" name="cod" class="form-control" value="<?= $edit_data['cod'] ?? '' ?>"
                                required>
                        </div>

                        <div class="col-12 mt-4 text-end">
                            <?php if ($edit_data): ?>
                                <a href="ongkir.php" class="btn btn-secondary me-2">Batal</a>
                            <?php endif; ?>
                            <button type="submit" name="save" class="btn btn-primary px-4">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>