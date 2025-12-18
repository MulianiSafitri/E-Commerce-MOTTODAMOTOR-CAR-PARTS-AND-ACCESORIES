<?php
// inc/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/functions.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MOTTODA MOTOR - Sparepart Mobil Terlengkap</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>

<body>

    <!-- Top Header -->
    <div class="top-header py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <a class="navbar-brand d-flex align-items-center text-dark" href="<?= base_url('index.php') ?>">
                    <img src="<?= base_url('assets/img/logo MD.jpg') ?>" alt="MOTTODA MOTOR"
                        style="height: 60px; width: 60px; margin-right: 15px; object-fit: cover;"
                        class="rounded-circle">
                    <div>
                        <h2 class="m-0 fw-bold" style="font-family: serif; letter-spacing: 1px;">MOTTODA<span
                                class="text-danger">MOTOR</span>.COM</h2>
                        <small class="text-muted" style="letter-spacing: 2px; font-size: 0.8rem;">CAR PARTS AND
                            ACCESORIES</small>
                    </div>
                </a>
            </div>

            <div class="d-flex align-items-center">
                <?php if (is_login()): ?>
                    <div class="dropdown me-4">
                        <a class="text-dark text-decoration-none dropdown-toggle fw-bold" href="#" id="userDropdown"
                            role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle fa-lg me-1"></i> <?= $_SESSION['user_name'] ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?= base_url('pages/orders.php') ?>">Pesanan Saya</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('pages/track_order.php') ?>">Lacak
                                    Pengiriman</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('pages/address_list.php') ?>">Alamat Saya</a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-danger"
                                    href="<?= base_url('inc/auth.php?action=logout') ?>">Logout</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="<?= base_url('pages/login.php') ?>"
                        class="btn btn-danger fw-bold px-4 me-4 rounded-1">Login</a>
                <?php endif; ?>

                <a href="<?= base_url('pages/cart.php') ?>"
                    class="text-dark text-decoration-none d-flex align-items-center">
                    <i class="fas fa-shopping-cart fa-2x me-2"></i>
                    <div class="lh-1 text-start">
                        <div class="fw-bold">Shoping Cart</div>
                        <div class="text-danger small">
                            <?php
                            $cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
                            echo $cart_count . ' item';
                            ?>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Bottom Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-black py-0">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white px-4 py-2 <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active-nav' : '' ?>"
                            href="<?= base_url('index.php') ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white px-4 py-2 <?= basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active-nav' : '' ?>"
                            href="<?= base_url('pages/about.php') ?>">About us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white px-4 py-2 <?= basename($_SERVER['PHP_SELF']) == 'katalog.php' ? 'active-nav' : '' ?>"
                            href="<?= base_url('pages/katalog.php') ?>">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white px-4 py-2 <?= basename($_SERVER['PHP_SELF']) == 'how_to_order.php' ? 'active-nav' : '' ?>"
                            href="<?= base_url('pages/how_to_order.php') ?>">How to order</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white px-4 py-2 <?= basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active-nav' : '' ?>"
                            href="<?= base_url('pages/contact.php') ?>">Contact us</a>
                    </li>
                </ul>

                <!-- Search Form -->
                <form class="d-flex my-2 my-lg-0" action="<?= base_url('pages/katalog.php') ?>" method="GET"
                    style="width: 300px;">
                    <div class="input-group">
                        <input class="form-control rounded-0 border-0 bg-warning text-white placeholder-white"
                            type="search" name="search" placeholder="Search" aria-label="Search"
                            style="background-color: #f39c12 !important; color: white;"
                            value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                        <button class="btn btn-warning rounded-0 border-0" type="submit"
                            style="background-color: #f39c12 !important;"><i
                                class="fas fa-search text-black"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Container Spacer -->
    <div style="min-height: 60vh;">