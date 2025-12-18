<?php
// pages/address_list.php
require_once '../inc/header.php';
check_login();

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM alamat_user WHERE user_id = $user_id ORDER BY is_default DESC";
$result = mysqli_query($conn, $query);
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Daftar Alamat Saya</h3>
        <a href="address_add.php" class="btn btn-primary-mottoda">+ Tambah Alamat Baru</a>
    </div>

    <div class="row">
        <?php if (mysqli_num_rows($result) == 0): ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted">Belum ada alamat tersimpan.</p>
            </div>
        <?php else: ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-6 mb-3">
                    <div class="card h-100 <?= $row['is_default'] ? 'border-danger' : '' ?>">
                        <div class="card-body">
                            <?php if ($row['is_default']): ?>
                                <span class="badge bg-danger mb-2">Utama</span>
                            <?php endif; ?>

                            <h5 class="card-title"><?= $row['nama_penerima'] ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted"><?= $row['no_hp'] ?></h6>
                            <p class="card-text">
                                <?= $row['alamat_lengkap'] ?><br>
                                <?= $row['kecamatan'] ?>, <?= $row['kota'] ?><br>
                                <?= $row['provinsi'] ?> - <?= $row['kode_pos'] ?>
                            </p>

                            <div class="mt-3">
                                <a href="address_edit.php?id=<?= $row['id'] ?>"
                                    class="btn btn-sm btn-outline-secondary">Ubah</a>
                                <!-- Fitur jadikan default jika belum -->
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../inc/footer.php'; ?>