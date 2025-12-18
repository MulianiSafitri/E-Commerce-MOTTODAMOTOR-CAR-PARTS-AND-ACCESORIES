<?php
// inc/admin_auth.php
session_start();
require_once 'config.php';
require_once 'functions.php';

// Cek Login Admin
function verify_admin()
{
    if (!isset($_SESSION['admin_id'])) {
        header("Location: ../admin/login.php");
        exit;
    }
}

// Handle Admin Login
if (isset($_POST['admin_login'])) {
    $username = clean_input($_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM admins WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $admin = mysqli_fetch_assoc($result);
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['nama_lengkap'];
            header("Location: ../admin/index.php");
            exit;
        }
    }

    $_SESSION['login_error'] = "Username atau Password salah!";
    header("Location: ../admin/login.php");
    exit;
}

// Handle Logout
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_name']);
    header("Location: ../admin/login.php");
    exit;
}
?>