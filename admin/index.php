<?php
// admin/index.php
require_once '../inc/admin_auth.php';
verify_admin();

// Get Stats
$total_products = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM products"));
$total_orders = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM transactions"));
$total_sales_row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_belanja) as total FROM transactions WHERE status = 'Selesai'"));
$total_sales = $total_sales_row['total'] ?: 0;

// Data for Charts (Sales per category)
$cat_sales_query = "SELECT c.nama_kategori, COUNT(ti.id) as terjual 
                    FROM transaction_items ti 
                    JOIN products p ON ti.product_id = p.id 
                    JOIN categories c ON p.category_id = c.id 
                    GROUP BY c.id";
$cat_res = mysqli_query($conn, $cat_sales_query);
$labels = [];
$data_vals = [];
while ($r = mysqli_fetch_assoc($cat_res)) {
    $labels[] = $r['nama_kategori'];
    $data_vals[] = $r['terjual'];
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <title>Dashboard Admin - MOTTODA MOTOR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

    <div class="d-flex">
        <!-- Sidebar -->
        <div class="bg-dark text-white p-3 vh-100 fixed-top" style="width: 250px; z-index: 1000;">
            <h4 class="text-danger fw-bold mb-4">MOTTODA ADMIN</h4>
            <ul class="nav flex-column">
                <li class="nav-item mb-2"><a href="index.php" class="nav-link text-white active"><i
                            class="fas fa-tachometer-alt me-2"></i> Dashboard</a></li>
                <li class="nav-item mb-2"><a href="products.php" class="nav-link text-white"><i
                            class="fas fa-box me-2"></i> Produk</a></li>
                <li class="nav-item mb-2"><a href="categories.php" class="nav-link text-white"><i
                            class="fas fa-tags me-2"></i> Kategori</a></li>
                <li class="nav-item mb-2"><a href="orders.php" class="nav-link text-white"><i
                            class="fas fa-shopping-cart me-2"></i> Pesanan</a></li>
                <li class="nav-item mb-2"><a href="ongkir.php" class="nav-link text-white"><i
                            class="fas fa-truck me-2"></i> Ongkir</a></li>
                <li class="nav-item mb-2"><a href="reports.php" class="nav-link text-white"><i
                            class="fas fa-file-pdf me-2"></i> Laporan</a></li>
                <li class="nav-item mt-4"><a href="../inc/admin_auth.php?logout=true" class="nav-link text-danger"><i
                            class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
            </ul>
        </div>

        <!-- Content -->
        <div class="flex-grow-1 p-4" style="margin-left: 250px;">
            <h2 class="mb-4">Dashboard Overview</h2>

            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="card text-white bg-primary shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">Total Produk</h5>
                            <h2 class="fw-bold"><?= $total_products ?></h2>
                            <i class="fas fa-box fa-2x opacity-50 position-absolute end-0 top-0 m-3"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-success shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">Total Pendapatan (Selesai)</h5>
                            <h2 class="fw-bold"><?= format_rupiah($total_sales) ?></h2>
                            <i class="fas fa-money-bill-wave fa-2x opacity-50 position-absolute end-0 top-0 m-3"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-warning shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">Total Transaksi</h5>
                            <h2 class="fw-bold"><?= $total_orders ?></h2>
                            <i class="fas fa-shopping-cart fa-2x opacity-50 position-absolute end-0 top-0 m-3"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-4">Statistik Penjualan per Kategori</h5>
                    <canvas id="salesChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($labels) ?>,
                datasets: [{
                    label: 'Jumlah Item Terjual',
                    data: <?= json_encode($data_vals) ?>,
                    backgroundColor: '#d32f2f'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                }
            }
        });
    </script>

</body>

</html>