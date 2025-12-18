<?php
// pages/contact.php
require_once __DIR__ . '/../inc/header.php';
?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">Contact Us</h1>
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Hubungi Kami</h4>
                            <p>Jika Anda memiliki pertanyaan atau membutuhkan bantuan, jangan ragu untuk menghubungi
                                kami melalui kontak di bawah ini:</p>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-map-marker-alt text-danger me-2"></i> Jl. Marelan IX
                                    No. 345, Medan</li>
                                <li class="mb-2"><i class="fas fa-phone text-danger me-2"></i> +62 823-7039-7109</li>
                                <li class="mb-2"><i class="fas fa-envelope text-danger me-2"></i> info@mottodamotor.com
                                </li>
                                <li class="mb-2"><i class="fab fa-whatsapp text-danger me-2"></i> +62 823-7039-7109</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h4>Lokasi Kami</h4>
                            <!-- Placeholder for map -->
                            <div class="bg-light d-flex align-items-center justify-content-center"
                                style="height: 200px; border: 1px solid #ddd;">
                                <span class="text-muted">Google Maps Area</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../inc/footer.php'; ?>