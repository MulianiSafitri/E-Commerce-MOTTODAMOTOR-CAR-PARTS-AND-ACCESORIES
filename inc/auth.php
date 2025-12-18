<?php
// inc/auth.php
session_start();
require_once 'config.php';
require_once 'functions.php';

$action = $_GET['action'] ?? '';

// LOGOUT
if ($action === 'logout') {
    unset($_SESSION['user_id']);
    unset($_SESSION['user_name']);
    session_destroy();
    redirect('pages/login.php');
}

// LOGIN PROCESSING
if (isset($_POST['login'])) {
    $email = clean_input($_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nama_lengkap'];

            // Redirect to intended page or home
            redirect('index.php');
        } else {
            flash_msg('login_error', 'Password salah!', 'danger');
            redirect('pages/login.php');
        }
    } else {
        flash_msg('login_error', 'Email tidak terdaftar!', 'danger');
        redirect('pages/login.php');
    }
}

// REGISTER PROCESSING
if (isset($_POST['register'])) {
    $nama = clean_input($_POST['nama']);
    $email = clean_input($_POST['email']);
    $no_hp = clean_input($_POST['no_hp']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Cek Email
    $check = mysqli_query($conn, "SELECT email FROM users WHERE email = '$email'");
    if (mysqli_num_rows($check) > 0) {
        flash_msg('register_error', 'Email sudah terdaftar!', 'danger');
        redirect('pages/register.php');
    } else {
        $query = "INSERT INTO users (nama_lengkap, email, password, no_hp) VALUES ('$nama', '$email', '$password', '$no_hp')";
        if (mysqli_query($conn, $query)) {
            flash_msg('login_msg', 'Registrasi berhasil! Silakan login.', 'success');
            redirect('pages/login.php');
        } else {
            flash_msg('register_error', 'Registrasi gagal: ' . mysqli_error($conn), 'danger');
            redirect('pages/register.php');
        }
    }
}
?>