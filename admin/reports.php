<?php
// admin/reports.php
require_once '../inc/admin_auth.php';
// require_once '../vendor/autoload.php'; // Uncomment if using Composer
// use Dompdf\Dompdf; // Uncomment if utilizing Dompdf

verify_admin();

// Check if Export Requested
if (isset($_GET['export']) && $_GET['export'] == 'pdf') {
    // Generate HTML for PDF
    $start_date = $_GET['start'] ?? date('Y-m-01');
    $end_date = $_GET['end'] ?? date('Y-m-d');

    $query = "SELECT t.*, u.nama_lengkap FROM transactions t JOIN users u ON t.user_id = u.id 
              WHERE t.status = 'Selesai' AND DATE(t.tanggal) BETWEEN '$start_date' AND '$end_date'";
    $result = mysqli_query($conn, $query);

    $html = '<html><head><style>
                table { border-collapse: collapse; width: 100%; }
                th, td { border: 1px solid #000; padding: 8px; }
             </style></head><body>';
    $html .= '<h2 style="text-align:center;">Laporan Penjualan MOTTODA MOTOR</h2>';
    $html .= '<p>Periode: ' . $start_date . ' s/d ' . $end_date . '</p>';
    $html .= '<table><thead><tr>
                <th>No Invoice</th>
                <th>Tanggal</th>
                <th>Customer</th>
                <th>Total</th>
              </tr></thead><tbody>';

    $total_omzet = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $html .= '<tr>
                    <td>' . $row['invoice_no'] . '</td>
                    <td>' . date('d-m-Y', strtotime($row['tanggal'])) . '</td>
                    <td>' . $row['nama_lengkap'] . '</td>
                    <td style="text-align:right;">Rp ' . number_format($row['total_bayar'], 0, ',', '.') . '</td>
                  </tr>';
        $total_omzet += $row['total_bayar'];
    }

    $html .= '</tbody><tfoot><tr>
                <th colspan="3" style="text-align:right;">TOTAL PENDAPATAN</th>
                <th style="text-align:right;">Rp ' . number_format($total_omzet, 0, ',', '.') . '</th>
              </tr></tfoot></table></body></html>';

    // Dompdf Simulation (Since we can't install library)
    // In real implementation:
    // $dompdf = new Dompdf();
    // $dompdf->loadHtml($html);
    // $dompdf->render();
    // $dompdf->stream("Laporan_Mottoda.pdf");
    // exit;

    // Fallback: Print View
    echo $html;
    echo "<script>window.print();</script>";
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Laporan - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="d-flex">
        <div class="bg-dark text-white p-3 vh-100 position-fixed" style="width: 250px;">
            <h4 class="text-danger fw-bold mb-4">MOTTODA ADMIN</h4>
            <ul class="nav flex-column">
                <li class="nav-item mb-2"><a href="index.php" class="nav-link text-white"><i
                            class="fas fa-tachometer-alt me-2"></i> Dashboard</a></li>
                <li class="nav-item mb-2"><a href="products.php" class="nav-link text-white"><i
                            class="fas fa-box me-2"></i> Produk</a></li>
                <li class="nav-item mb-2"><a href="categories.php" class="nav-link text-white"><i
                            class="fas fa-tags me-2"></i> Kategori</a></li>
                <li class="nav-item mb-2"><a href="orders.php" class="nav-link text-white"><i
                            class="fas fa-shopping-cart me-2"></i> Pesanan</a></li>
                <li class="nav-item mb-2"><a href="ongkir.php" class="nav-link text-white"><i
                            class="fas fa-truck me-2"></i> Ongkir</a></li>
                <li class="nav-item mb-2"><a href="reports.php" class="nav-link text-white active bg-danger rounded"><i
                            class="fas fa-file-pdf me-2"></i> Laporan</a></li>
                <li class="nav-item mt-4"><a href="../inc/admin_auth.php?logout=true" class="nav-link text-danger"><i
                            class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
            </ul>
        </div>

        <div class="flex-grow-1 p-4" style="margin-left: 250px;">
            <h2 class="mb-4">Laporan Penjualan</h2>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="" method="GET" class="row g-3 align-items-end" target="_blank">
                        <input type="hidden" name="export" value="pdf">
                        <div class="col-auto">
                            <label class="form-label">Dari Tanggal</label>
                            <input type="date" name="start" class="form-control" value="<?= date('Y-m-01') ?>">
                        </div>
                        <div class="col-auto">
                            <label class="form-label">Sampai Tanggal</label>
                            <input type="date" name="end" class="form-control" value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-danger"><i class="fas fa-file-pdf"></i> Download PDF
                                (Print)</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>