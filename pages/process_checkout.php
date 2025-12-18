<?php
// pages/process_checkout.php
session_start();
require_once '../inc/config.php';
require_once '../inc/functions.php';

check_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $address_text = clean_input($_POST['address_text']);

    // Courier Data: "JNE REG|20000"
    $courier_data = explode('|', $_POST['courier']);
    $courier_name = clean_input($courier_data[0]);
    $ongkir_cost = (int) $courier_data[1];

    $payment = clean_input($_POST['payment_method']);

    // Calculate Totals again for security
    $cart = get_cart_details();
    if (empty($cart['items']))
        redirect('pages/cart.php');

    $subtotal = $cart['total'];
    $total_bayar = $subtotal + $ongkir_cost;

    // Generate Invoice
    $invoice = 'INV-' . date('ymd') . rand(1000, 9999);

    // Start Transaction
    mysqli_begin_transaction($conn);

    try {
        // 1. Insert Transaction
        $query_trans = "INSERT INTO transactions (user_id, invoice_no, total_belanja, total_ongkir, total_bayar, alamat_pengiriman, kurir, metode_bayar, status)
                        VALUES ('$user_id', '$invoice', '$subtotal', '$ongkir_cost', '$total_bayar', '$address_text', '$courier_name', '$payment', 'Pending')";

        if (!mysqli_query($conn, $query_trans)) {
            throw new Exception("Error Insert Transaction: " . mysqli_error($conn));
        }

        $transaction_id = mysqli_insert_id($conn);

        // 2. Insert Items & Update Stock
        foreach ($cart['items'] as $item) {
            $prod_id = $item['id'];
            $qty = $item['qty'];
            $price = $item['harga'];
            $item_subtotal = $item['subtotal'];

            // Cek Stok
            $stok_check = mysqli_query($conn, "SELECT stok FROM products WHERE id = $prod_id FOR UPDATE");
            $stok_row = mysqli_fetch_assoc($stok_check);
            if ($stok_row['stok'] < $qty) {
                throw new Exception("Stok produk '" . $item['nama_produk'] . "' tidak mencukupi.");
            }

            // Insert Item
            $q_item = "INSERT INTO transaction_items (transaction_id, product_id, qty, harga, subtotal)
                       VALUES ('$transaction_id', '$prod_id', '$qty', '$price', '$item_subtotal')";
            if (!mysqli_query($conn, $q_item))
                throw new Exception("Error Insert Item");

            // Update Stock & Terjual
            $q_update = "UPDATE products SET stok = stok - $qty, terjual = terjual + $qty WHERE id = $prod_id";
            if (!mysqli_query($conn, $q_update))
                throw new Exception("Error Update Stock");
        }

        // 3. Commit
        mysqli_commit($conn);

        // 4. Clear Cart
        unset($_SESSION['cart']);

        // 5. Send Email (Simulation)
        // In real world: use PHPMailer here.
        // mail($user_email, "Invoice $invoice", "Terima kasih...");

        flash_msg('order_success', "Pesanan Berhasil! Invoice: $invoice", 'success');
        redirect('pages/orders.php');

    } catch (Exception $e) {
        mysqli_rollback($conn);
        flash_msg('checkout_error', 'Gagal Checkout: ' . $e->getMessage(), 'danger');
        redirect('pages/cart.php');
    }
} else {
    redirect('index.php');
}
?>