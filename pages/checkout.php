<?php
// pages/checkout.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../inc/functions.php';

check_login();

// 1. Cek User Address
$address = get_user_address($_SESSION['user_id']);
if (!$address) {
    flash_msg('addr_err', 'Silakan tambah alamat pengiriman terlebih dahulu!', 'warning');
    redirect('pages/address_add.php');
}

// 2. Cek Cart
$cart_details = get_cart_details();
if (empty($cart_details['items'])) {
    redirect('pages/cart.php');
}

$items = $cart_details['items'];
$subtotal = $cart_details['total'];

// 3. Get Ongkir
$ongkir_data = get_ongkir_options($address['kecamatan'], $address['kota']);
// Default if not found (Fallback logic or hard error handling. For demo uses fallback fixed cost or alert)
$shipping_options = [];
if ($ongkir_data) {
    $shipping_options = [
        'JNE REG' => $ongkir_data['jne_reg'],
        'JNT EZ' => $ongkir_data['jnt'],
        'SiCepat REG' => $ongkir_data['sicepat'],
        'COD' => $ongkir_data['cod'] // Optional if implementing specific COD check
    ];
} else {
    // Fallback Dummy
    $shipping_options = [
        'JNE REG' => 20000,
        'JNT EZ' => 22000
    ];
}

require_once '../inc/header.php';
?>

<div class="container py-5">
    <form action="process_checkout.php" method="POST" id="checkoutForm">
        <h2 class="fw-bold mb-4">Checkout & Pembayaran</h2>

        <div class="row">
            <!-- Left: Address & Courier -->
            <div class="col-lg-7">
                <!-- Alamat Card -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header bg-white fw-bold">Alamat Pengiriman</div>
                    <div class="card-body">
                        <h5><?= $address['nama_penerima'] ?> <small
                                class="text-muted">(<?= $address['no_hp'] ?>)</small></h5>
                        <p class="mb-2">
                            <?= $address['alamat_lengkap'] ?><br>
                            <?= $address['kecamatan'] ?>, <?= $address['kota'] ?>, <?= $address['provinsi'] ?><br>
                            Kode Pos: <?= $address['kode_pos'] ?>
                        </p>
                        <a href="address_list.php" class="btn btn-sm btn-outline-secondary">Ganti Alamat</a>
                        <input type="hidden" name="address_text"
                            value="<?= $address['alamat_lengkap'] . ', ' . $address['kecamatan'] . ', ' . $address['kota'] ?>">
                    </div>
                </div>

                <!-- Kurir Selection -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header bg-white fw-bold">Pilih Pengiriman</div>
                    <div class="card-body">
                        <?php foreach ($shipping_options as $courier => $cost): ?>
                            <div class="form-check p-3 border rounded mb-2 cursor-pointer">
                                <input class="form-check-input mt-1" type="radio" name="courier"
                                    id="<?= str_replace(' ', '_', $courier) ?>" value="<?= $courier . '|' . $cost ?>"
                                    required onclick="updateTotal(<?= $cost ?>)">
                                <label class="form-check-label w-100 d-flex justify-content-between"
                                    for="<?= str_replace(' ', '_', $courier) ?>">
                                    <span><strong><?= $courier ?></strong></span>
                                    <span class="text-danger fw-bold"><?= format_rupiah($cost) ?></span>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header bg-white fw-bold">Metode Pembayaran</div>
                    <div class="card-body">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment_method" value="Transfer BCA"
                                required>
                            <label class="form-check-label">Transfer Bank BCA (Manual)</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment_method" value="QRIS">
                            <label class="form-check-label">QRIS (Otomatis)</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment_method" value="COD">
                            <label class="form-check-label">Bayar di Tempat (COD)</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Summary -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-bold">Ringkasan Pesanan</div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush mb-3">
                            <?php foreach ($items as $item): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <small class="d-block text-truncate"
                                            style="max-width: 200px;"><?= $item['nama_produk'] ?></small>
                                        <small class="text-muted">x <?= $item['qty'] ?></small>
                                    </div>
                                    <span><?= format_rupiah($item['subtotal']) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal Produk</span>
                            <span class="fw-bold"><?= format_rupiah($subtotal) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Biaya Pengiriman</span>
                            <span class="fw-bold" id="shipping_display">Rp 0</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fs-5 fw-bold">Total Belanja</span>
                            <span class="fs-5 fw-bold text-danger"
                                id="total_display"><?= format_rupiah($subtotal) ?></span>
                        </div>

                        <input type="hidden" name="total_belanja" value="<?= $subtotal ?>">
                        <button type="submit" class="btn btn-primary-mottoda w-100 py-3 fw-bold">BUAT PESANAN</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    let subtotal = <?= $subtotal ?>;

    function formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
    }

    function updateTotal(shippingCost) {
        document.getElementById('shipping_display').innerText = formatRupiah(shippingCost);
        document.getElementById('total_display').innerText = formatRupiah(subtotal + shippingCost);
    }
</script>

<?php require_once '../inc/footer.php'; ?>