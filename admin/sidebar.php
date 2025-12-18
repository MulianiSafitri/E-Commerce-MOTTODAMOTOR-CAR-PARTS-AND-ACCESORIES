<?php
// admin/sidebar.php
?>
<div class="bg-dark text-white p-3 vh-100 position-fixed" style="width: 250px;">
    <h4 class="text-danger fw-bold mb-4">MOTTODA ADMIN</h4>
    <ul class="nav flex-column">
        <li class="nav-item mb-2"><a href="index.php" class="nav-link text-white"><i
                    class="fas fa-tachometer-alt me-2"></i> Dashboard</a></li>
        <li class="nav-item mb-2"><a href="products.php" class="nav-link text-white"><i class="fas fa-box me-2"></i>
                Produk</a></li>
        <li class="nav-item mb-2"><a href="categories.php" class="nav-link text-white"><i class="fas fa-tags me-2"></i>
                Kategori</a></li>
        <li class="nav-item mb-2"><a href="orders.php" class="nav-link text-white"><i
                    class="fas fa-shopping-cart me-2"></i> Pesanan</a></li>
        <li class="nav-item mb-2"><a href="ongkir.php" class="nav-link text-white"><i class="fas fa-truck me-2"></i>
                Ongkir</a></li>
        <li class="nav-item mb-2"><a href="reports.php" class="nav-link text-white"><i class="fas fa-file-pdf me-2"></i>
                Laporan</a></li>
        <li class="nav-item mt-4"><a href="../inc/admin_auth.php?logout=true" class="nav-link text-danger"><i
                    class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
    </ul>
</div>