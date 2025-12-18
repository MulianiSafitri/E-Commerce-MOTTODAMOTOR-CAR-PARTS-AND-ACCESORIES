<?php
// admin/product_add.php
require_once '../inc/admin_auth.php';
verify_admin();

// Fetch Categories
$cats = mysqli_query($conn, "SELECT * FROM categories");

if (isset($_POST['save'])) {
    $nama = clean_input($_POST['nama_produk']);
    $cat_id = clean_input($_POST['category_id']);
    $desc = clean_input($_POST['deskripsi']);
    $harga = clean_input($_POST['harga']);
    $stok = clean_input($_POST['stok']);
    $berat = clean_input($_POST['berat']);
    $merek = clean_input($_POST['merek']);
    $tipe = clean_input($_POST['tipe_mobil']);
    $kondisi = clean_input($_POST['kondisi']);

    // Slug
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $nama)));

    // Image Upload
    $gambar = '';
    if (!empty($_FILES['gambar']['name'])) {
        $gambar = time() . '_' . $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], '../assets/img/products/' . $gambar);
    }

    $query = "INSERT INTO products (category_id, nama_produk, slug, deskripsi, harga, stok, berat, merek, tipe_mobil, kondisi, gambar_utama)
              VALUES ('$cat_id', '$nama', '$slug', '$desc', '$harga', '$stok', '$berat', '$merek', '$tipe', '$kondisi', '$gambar')";

    if (mysqli_query($conn, $query)) {
        header("Location: products.php");
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Tambah Produk - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container py-5 w-50">
        <h3 class="mb-4">Tambah Produk Baru</h3>
        <?php if (isset($error))
            echo "<div class='alert alert-danger'>$error</div>"; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label>Nama Produk</label>
                <input type="text" name="nama_produk" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Kategori</label>
                <select name="category_id" class="form-select" required>
                    <?php while ($c = mysqli_fetch_assoc($cats)): ?>
                        <option value="<?= $c['id'] ?>"><?= $c['nama_kategori'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Harga (Rp)</label>
                    <input type="number" name="harga" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Stok</label>
                    <input type="number" name="stok" class="form-control" required>
                </div>
            </div>
            <div class="mb-3">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3"></textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Merek</label>
                    <input type="text" name="merek" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Tipe Mobil</label>
                    <input type="text" name="tipe_mobil" class="form-control">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Berat (gram)</label>
                    <input type="number" name="berat" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Kondisi</label>
                    <select name="kondisi" class="form-select">
                        <option value="Baru">Baru</option>
                        <option value="Bekas">Bekas</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label>Gambar Utama</label>
                <input type="file" name="gambar" class="form-control">
            </div>

            <a href="products.php" class="btn btn-secondary">Batal</a>
            <button type="submit" name="save" class="btn btn-primary">Simpan Produk</button>
        </form>
    </div>
</body>

</html>