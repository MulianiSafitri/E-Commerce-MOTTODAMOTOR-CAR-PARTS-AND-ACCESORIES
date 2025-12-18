<?php
// pages/address_edit.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../inc/functions.php';

check_login();

$user_id = $_SESSION['user_id'];
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch existing data
$query = "SELECT * FROM alamat_user WHERE id = $id AND user_id = $user_id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    // Address not found or doesn't belong to user
    redirect('pages/address_list.php');
}

$data = mysqli_fetch_assoc($result);

if (isset($_POST['update_address'])) {
    $nama = clean_input($_POST['nama_penerima']);
    $hp = clean_input($_POST['no_hp']);
    $prov = clean_input($_POST['provinsi']);
    $kota = clean_input($_POST['kota']);
    $kec = clean_input($_POST['kecamatan']);
    $alamat = clean_input($_POST['alamat_lengkap']);
    $kodepos = clean_input($_POST['kode_pos']);

    $update_query = "UPDATE alamat_user SET 
                     nama_penerima = '$nama',
                     no_hp = '$hp',
                     provinsi = '$prov',
                     kota = '$kota',
                     kecamatan = '$kec',
                     alamat_lengkap = '$alamat',
                     kode_pos = '$kodepos'
                     WHERE id = $id AND user_id = $user_id";

    if (mysqli_query($conn, $update_query)) {
        redirect('pages/address_list.php');
    } else {
        echo "<script>alert('Gagal update alamat: " . mysqli_error($conn) . "');</script>";
        // Refresh data only if consistent with expectation, but usually just show error.
    }
}

require_once '../inc/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-dark text-white p-4 position-relative">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning rounded-circle p-3 me-3 d-flex align-items-center justify-content-center"
                            style="width: 60px; height: 60px;">
                            <i class="fas fa-edit fa-2x text-white"></i>
                        </div>
                        <div>
                            <h4 class="mb-1 fw-bold">Ubah Alamat</h4>
                            <p class="mb-0 text-white-50 small">Perbarui detail pengiriman Anda.</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4 p-md-5 bg-light">
                    <form action="" method="POST">
                        <h5 class="mb-4 text-dark fw-bold border-bottom pb-2"><i
                                class="fas fa-user-tag me-2 text-danger"></i>Informasi Penerima</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama Penerima</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i
                                            class="fas fa-user text-muted"></i></span>
                                    <input type="text" name="nama_penerima" class="form-control border-start-0 ps-0"
                                        placeholder="Contoh: Budi Santoso" required
                                        value="<?= htmlspecialchars($data['nama_penerima']) ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">No. Handphone</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i
                                            class="fas fa-phone text-muted"></i></span>
                                    <input type="text" name="no_hp" class="form-control border-start-0 ps-0"
                                        placeholder="Contoh: 081234567890" required
                                        value="<?= htmlspecialchars($data['no_hp']) ?>">
                                </div>
                            </div>
                        </div>

                        <h5 class="mb-4 text-dark fw-bold border-bottom pb-2"><i
                                class="fas fa-map-marked-alt me-2 text-danger"></i>Detail Lokasi</h5>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Alamat Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i
                                        class="fas fa-home text-muted"></i></span>
                                <textarea name="alamat_lengkap" class="form-control border-start-0 ps-0" rows="3"
                                    placeholder="Nama Jalan, No. Rumah, RT/RW, Patokan..."
                                    required><?= htmlspecialchars($data['alamat_lengkap']) ?></textarea>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Provinsi</label>
                                <select name="provinsi" class="form-select" required>
                                    <option value="" disabled>Pilih Provinsi</option>
                                    <?php
                                    $provinsis = ["DKI Jakarta", "Jawa Barat", "Jawa Timur", "Banten", "Sumatera Utara"];
                                    foreach ($provinsis as $p) {
                                        $selected = ($data['provinsi'] == $p) ? 'selected' : '';
                                        echo "<option value=\"$p\" $selected>$p</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Kota/Kabupaten</label>
                                <select name="kota" class="form-select" required>
                                    <option value="" disabled>Pilih Kota/Kabupaten</option>
                                    <?php
                                    $kotas = ["Jakarta Selatan", "Jakarta Pusat", "Bandung", "Bekasi", "Tangerang", "Surabaya", "Medan", "Binjai"];
                                    foreach ($kotas as $k) {
                                        $selected = ($data['kota'] == $k) ? 'selected' : '';
                                        echo "<option value=\"$k\" $selected>$k</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Kecamatan</label>
                                <select name="kecamatan" class="form-select" required>
                                    <option value="" disabled>Pilih Kecamatan</option>
                                    <?php
                                    $kecamatans = ["Tebet", "Menteng", "Coblong", "Bekasi Barat", "Cipondoh", "Gubeng", "Medan Kota", "Medan Baru", "Medan Marelan", "Medan Labuhan", "Medan Timur", "Binjai Kota"];
                                    foreach ($kecamatans as $kc) {
                                        $selected = ($data['kecamatan'] == $kc) ? 'selected' : '';
                                        echo "<option value=\"$kc\" $selected>$kc</option>";
                                    }
                                    ?>
                                </select>
                                <div class="form-text text-muted"><i class="fas fa-info-circle me-1"></i>Pilih sesuai
                                    data ongkir yang tersedia.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Kode Pos</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i
                                            class="fas fa-mail-bulk text-muted"></i></span>
                                    <input type="text" name="kode_pos" class="form-control border-start-0 ps-0"
                                        placeholder="Contoh: 12810" required
                                        value="<?= htmlspecialchars($data['kode_pos']) ?>">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-5">
                            <a href="address_list.php" class="btn btn-outline-secondary px-4 fw-bold rounded-pill">
                                <i class="fas fa-arrow-left me-2"></i>Batal
                            </a>
                            <button type="submit" name="update_address"
                                class="btn btn-primary-mottoda px-5 fw-bold rounded-pill shadow-sm">
                                <i class="fas fa-save me-2"></i>Update Alamat
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../inc/footer.php'; ?>