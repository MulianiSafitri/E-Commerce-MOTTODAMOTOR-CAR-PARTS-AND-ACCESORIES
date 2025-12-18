<?php
// admin/login.php
session_start();
if (isset($_SESSION['admin_id']))
    header("Location: index.php");
?>
<!DOCTYPE html>
<html>

<head>
    <title>Admin Login - MOTTODA MOTOR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-dark d-flex align-items-center justify-content-center" style="height: 100vh;">
    <div class="card p-4" style="width: 350px;">
        <h4 class="text-center mb-4">Admin Panel</h4>
        <?php if (isset($_SESSION['login_error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['login_error'];
            unset($_SESSION['login_error']); ?></div>
        <?php endif; ?>
        <form action="../inc/admin_auth.php" method="POST">
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" name="admin_login" class="btn btn-danger w-100">Login</button>
        </form>
    </div>
</body>

</html>