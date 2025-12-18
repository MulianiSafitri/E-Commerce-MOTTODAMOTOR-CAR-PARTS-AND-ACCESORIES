<?php
// pages/address_add.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../inc/functions.php';

check_login();

if (isset($_POST['save_address'])) {
    $user_id = $_SESSION['user_id'];
    $nama = clean_input($_POST['nama_penerima']);
    $hp = clean_input($_POST['no_hp']);
    $prov = clean_input($_POST['provinsi']);
    $kota = clean_input($_POST['kota']);
    $kec = clean_input($_POST['kecamatan']);
    $alamat = clean_input($_POST['alamat_lengkap']);
    $kodepos = clean_input($_POST['kode_pos']);

    // Cek apakah ini alamat pertama? Jika ya, set default
    $check = mysqli_query($conn, "SELECT id FROM alamat_user WHERE user_id = $user_id");
    $is_default = (mysqli_num_rows($check) == 0) ? 1 : 0;

    $query = "INSERT INTO alamat_user (user_id, nama_penerima, no_hp, provinsi, kota, kecamatan, alamat_lengkap, kode_pos, is_default)
              VALUES ('$user_id', '$nama', '$hp', '$prov', '$kota', '$kec', '$alamat', '$kodepos', '$is_default')";

    if (mysqli_query($conn, $query)) {
        redirect('pages/address_list.php');
    } else {
        echo "<script>alert('Gagal simpan alamat: " . mysqli_error($conn) . "');</script>";
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
                        <div class="bg-danger rounded-circle p-3 me-3 d-flex align-items-center justify-content-center"
                            style="width: 60px; height: 60px;">
                            <i class="fas fa-map-marker-alt fa-2x text-white"></i>
                        </div>
                        <div>
                            <h4 class="mb-1 fw-bold">Tambah Alamat Baru</h4>
                            <p class="mb-0 text-white-50 small">Lengkapi detail pengiriman Anda dengan benar.</p>
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
                                        placeholder="Contoh: Budi Santoso" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">No. Handphone</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i
                                            class="fas fa-phone text-muted"></i></span>
                                    <input type="text" name="no_hp" class="form-control border-start-0 ps-0"
                                        placeholder="Contoh: 081234567890" required>
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
                                    placeholder="Nama Jalan, No. Rumah, RT/RW, Patokan..." required></textarea>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Provinsi</label>
                                <select name="provinsi" class="form-select" required>
                                    <option value="" selected disabled>Pilih Provinsi</option>
                                    <option value="DKI Jakarta">DKI Jakarta</option>
                                    <option value="Jawa Barat">Jawa Barat</option>
                                    <option value="Jawa Timur">Jawa Timur</option>
                                    <option value="Banten">Banten</option>
                                    <option value="Sumatera Utara">Sumatera Utara</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Kota/Kabupaten</label>
                                <select name="kota" class="form-select" required>
                                    <option value="" selected disabled>Pilih Kota/Kabupaten</option>
                                    <option value="Jakarta Selatan">Jakarta Selatan</option>
                                    <option value="Jakarta Pusat">Jakarta Pusat</option>
                                    <option value="Bandung">Bandung</option>
                                    <option value="Bekasi">Bekasi</option>
                                    <option value="Tangerang">Tangerang</option>
                                    <option value="Surabaya">Surabaya</option>
                                    <option value="Medan">Medan</option>
                                    <option value="Binjai">Binjai</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Kecamatan</label>
                                <select name="kecamatan" class="form-select" required>
                                    <option value="" selected disabled>Pilih Kecamatan</option>
                                    <option value="Tebet">Tebet</option>
                                    <option value="Menteng">Menteng</option>
                                    <option value="Coblong">Coblong</option>
                                    <option value="Bekasi Barat">Bekasi Barat</option>
                                    <option value="Cipondoh">Cipondoh</option>
                                    <option value="Gubeng">Gubeng</option>
                                    <option value="Medan Kota">Medan Kota</option>
                                    <option value="Medan Baru">Medan Baru</option>
                                    <option value="Medan Marelan">Medan Marelan</option>
                                    <option value="Medan Labuhan">Medan Labuhan</option>
                                    <option value="Medan Timur">Medan Timur</option>
                                    <option value="Binjai Kota">Binjai Kota</option>
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
                                        placeholder="Contoh: 12810" required>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-5">
                            <a href="address_list.php" class="btn btn-outline-secondary px-4 fw-bold rounded-pill">
                                <i class="fas fa-arrow-left me-2"></i>Batal
                            </a>
                            <button type="submit" name="save_address"
                                class="btn btn-primary-mottoda px-5 fw-bold rounded-pill shadow-sm">
                                <i class="fas fa-save me-2"></i>Simpan Alamat
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../inc/footer.php'; ?>