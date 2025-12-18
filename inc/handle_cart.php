<?php
// inc/handle_cart.php
session_start();
require_once 'config.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $product_id = (int) $_POST['product_id'];
        $qty = isset($_POST['qty']) ? (int) $_POST['qty'] : 1;

        add_to_cart($product_id, $qty);

        // Simpan referer biar balik ke halaman sebelumnya
        $referer = $_SERVER['HTTP_REFERER'] ?? base_url('pages/katalog.php');
        redirect(str_replace(base_url(), '', $referer));
    }

    if ($action === 'update') {
        $product_id = (int) $_POST['product_id'];
        $qty = (int) $_POST['qty'];
        update_cart($product_id, $qty);
        redirect('pages/cart.php');
    }

    if ($action === 'remove') {
        $product_id = (int) $_POST['product_id'];
        unset($_SESSION['cart'][$product_id]);
        redirect('pages/cart.php');
    }
}
?>