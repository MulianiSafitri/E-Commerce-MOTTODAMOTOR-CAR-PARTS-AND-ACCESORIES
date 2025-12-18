<?php
// pages/login.php
session_start();
require_once '../inc/functions.php'; // Utk helper functions

if (is_login())
    redirect('index.php');
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MOTTODA MOTOR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>

<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">

    <div class="card shadow-lg border-0" style="width: 400px; max-width: 90%;">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <h3 class="fw-bold text-danger">MOTTODA MOTOR</h3>
                <p class="text-secondary">Login untuk melanjutkan belanja</p>
            </div>

            <?= flash_msg('login_error') ?>
            <?= flash_msg('login_msg') ?>

            <form action="<?= base_url('inc/auth.php') ?>" method="POST">
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="d-grid mb-3">
                    <button type="submit" name="login" class="btn btn-primary-mottoda py-2">MASUK</button>
                </div>
                <div class="text-center">
                    <a href="register.php" class="text-decoration-none text-danger">Belum punya akun? Daftar</a>
                </div>
            </form>
        </div>
    </div>

</body>

</html>