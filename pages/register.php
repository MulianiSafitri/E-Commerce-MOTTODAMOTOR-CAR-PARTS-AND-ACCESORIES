<?php
// pages/register.php
session_start();
require_once '../inc/functions.php';

if (is_login())
    redirect('index.php');
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - MOTTODA MOTOR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>

<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">

    <div class="card shadow-lg border-0 mt-5 mb-5" style="width: 500px; max-width: 90%;">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <h3 class="fw-bold text-danger">MOTTODA MOTOR</h3>
                <p class="text-secondary">Daftar akun baru</p>
            </div>

            <?= flash_msg('register_error') ?>

            <form action="<?= base_url('inc/auth.php') ?>" method="POST">
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">No. Handphone</label>
                    <input type="text" name="no_hp" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required minlength="6">
                </div>
                <div class="d-grid mb-3">
                    <button type="submit" name="register" class="btn btn-primary-mottoda py-2">DAFTAR SEKARANG</button>
                </div>
                <div class="text-center">
                    <a href="login.php" class="text-decoration-none text-danger">Sudah punya akun? Login</a>
                </div>
            </form>
        </div>
    </div>

</body>

</html>