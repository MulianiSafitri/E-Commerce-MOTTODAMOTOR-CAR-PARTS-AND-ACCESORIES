<?php
// admin/product_edit.php
require_once '../inc/admin_auth.php';
verify_admin();

$id = $_GET['id'];
$product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id = $id"));
$cats = mysqli_query($conn, "SELECT * FROM categories");

if (isset($_POST['update'])) {
    $nama = clean_input($_POST['nama_produk']);
    $cat_id = clean_input($_POST['category_id']);
    $desc = clean_input($_POST['deskripsi']);
    $harga = clean_input($_POST['harga']);
    $stok = clean_input($_POST['stok']);
    $berat = clean_input($_POST['berat']);
    $merek = clean_input($_POST['merek']);
    $tipe = clean_input($_POST['tipe_mobil']);
    $kondisi = clean_input($_POST['kondisi']);

    // Slug Update Optional (Usually disabled to keep SEO)

    $query = "UPDATE products SET category_id='$cat_id', nama_produk='$nama', deskripsi='$desc', harga='$harga', stok='$stok', berat='$berat', merek='$merek', tipe_mobil='$tipe', kondisi='$kondisi' WHERE id=$id";

    if (!empty($_FILES['gambar']['name'])) {
        $gambar = time() . '_' . $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], '../assets/img/products/' . $gambar);
        $query = "UPDATE products SET category_id='$cat_id', nama_produk='$nama', deskripsi='$desc', harga='$harga', stok='$stok', berat='$berat', merek='$merek', tipe_mobil='$tipe', kondisi='$kondisi', gambar_utama='$gambar' WHERE id=$id";
    }

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
    <title>Edit Produk - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container py-5 w-50">
        <h3 class="mb-4">Edit Produk</h3>
        <?php if (isset($error))
            echo "<div class='alert alert-danger'>$error</div>"; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label>Nama Produk</label>
                <input type="text" name="nama_produk" class="form-control" value="<?= $product['nama_produk'] ?>"
                    required>
            </div>
            <div class="mb-3">
                <label>Kategori</label>
                <select name="category_id" class="form-select" required>
                    <?php while ($c = mysqli_fetch_assoc($cats)): ?>
                        <option value="<?= $c['id'] ?>" <?= $c['id'] == $product['category_id'] ? 'selected' : '' ?>>
                            <?= $c['nama_kategori'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Harga (Rp)</label>
                    <input type="number" name="harga" class="form-control" value="<?= $product['harga'] ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Stok</label>
                    <input type="number" name="stok" class="form-control" value="<?= $product['stok'] ?>" required>
                </div>
            </div>
            <div class="mb-3">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3"><?= $product['deskripsi'] ?></textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Merek</label>
                    <input type="text" name="merek" class="form-control" value="<?= $product['merek'] ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Tipe Mobil</label>
                    <input type="text" name="tipe_mobil" class="form-control" value="<?= $product['tipe_mobil'] ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Berat (gram)</label>
                    <input type="number" name="berat" class="form-control" value="<?= $product['berat'] ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Kondisi</label>
                    <select name="kondisi" class="form-select">
                        <option value="Baru" <?= $product['kondisi'] == 'Baru' ? 'selected' : '' ?>>Baru</option>
                        <option value="Bekas" <?= $product['kondisi'] == 'Bekas' ? 'selected' : '' ?>>Bekas</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label>Ganti Gambar Utama (Opsional)</label>
                <input type="file" name="gambar" class="form-control">
                <small>Gambar saat ini: <?= $product['gambar_utama'] ?></small>
            </div>

            <a href="products.php" class="btn btn-secondary">Batal</a>
            <button type="submit" name="update" class="btn btn-primary">Update Produk</button>
        </form>
    </div>
</body>

</html>