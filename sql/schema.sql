-- Database Schema for MOTTODA MOTOR
-- E-Commerce Sparepart Mobil

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------

-- Table structure for table `admins`
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `users`
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `alamat_user`
CREATE TABLE `alamat_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `nama_penerima` varchar(100) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `provinsi` varchar(50) NOT NULL,
  `kota` varchar(50) NOT NULL,
  `kecamatan` varchar(50) NOT NULL,
  `alamat_lengkap` text NOT NULL,
  `kode_pos` varchar(10) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `categories`
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `products`
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `nama_produk` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `deskripsi` text NOT NULL,
  `harga` decimal(15,2) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `berat` int(11) NOT NULL COMMENT 'gram',
  `merek` varchar(50) DEFAULT NULL,
  `tipe_mobil` varchar(100) DEFAULT NULL,
  `kondisi` enum('Baru','Bekas') NOT NULL DEFAULT 'Baru',
  `gambar_utama` varchar(255) DEFAULT NULL,
  `terjual` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `product_images`
CREATE TABLE `product_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `gambar` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `ongkir`
CREATE TABLE `ongkir` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provinsi` varchar(50) NOT NULL,
  `kota` varchar(50) NOT NULL,
  `kecamatan` varchar(50) NOT NULL,
  `jne_reg` decimal(10,2) NOT NULL,
  `jnt` decimal(10,2) NOT NULL,
  `sicepat` decimal(10,2) NOT NULL,
  `cod` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `transactions`
CREATE TABLE `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `invoice_no` varchar(20) NOT NULL,
  `tanggal` datetime NOT NULL DEFAULT current_timestamp(),
  `total_belanja` decimal(15,2) NOT NULL,
  `total_ongkir` decimal(15,2) NOT NULL,
  `total_bayar` decimal(15,2) NOT NULL,
  `alamat_pengiriman` text NOT NULL,
  `kurir` varchar(50) NOT NULL,
  `metode_bayar` varchar(50) NOT NULL,
  `status` enum('Pending','Dibayar','Dikirim','Selesai','Batal') NOT NULL DEFAULT 'Pending',
  `bukti_bayar` varchar(255) DEFAULT NULL,
  `resi` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_no` (`invoice_no`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `transaction_items`
CREATE TABLE `transaction_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `harga` decimal(15,2) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_id` (`transaction_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `tfidf_cache` (Optional for performance)
CREATE TABLE `tfidf_cache` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `term` varchar(100) NOT NULL,
  `tfidf_score` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert Default Admin
INSERT INTO `admins` (`id`, `username`, `password`, `nama_lengkap`, `last_login`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator Mottoda', NULL);
-- Password: password

-- Insert Categories
INSERT INTO `categories` (`id`, `nama_kategori`, `slug`) VALUES
(1, 'Mesin', 'mesin'),
(2, 'Oli & Cairan', 'oli-cairan'),
(3, 'Kaki-kaki', 'kaki-kaki'),
(4, 'Kelistrikan', 'kelistrikan'),
(5, 'Filter', 'filter'),
(6, 'Rem & Pengereman', 'rem-pengereman'),
(7, 'Aksesori Eksterior', 'aksesori-eksterior'),
(8, 'Interior', 'interior');

-- Insert Sample Products
INSERT INTO `products` (`id`, `category_id`, `nama_produk`, `slug`, `deskripsi`, `harga`, `stok`, `berat`, `merek`, `tipe_mobil`, `kondisi`, `gambar_utama`, `terjual`) VALUES
(1, 2, 'Oli Mesin TMO 10W-40 Galon 4 Liter', 'oli-mesin-tmo-10w-40', 'Oli mesin Toyota Motor Oil (TMO) Synthetic Formula 10W-40. Cocok untuk mobil bensin Toyota seperti Avanza, Innova, Agya.', 180000, 50, 4000, 'Toyota', 'Semua Mobil Toyota', 'Baru', 'oli_tmo_10w40.jpg', 10),
(2, 5, 'Filter Oli Avanza Grand Xenia Rush Terios', 'filter-oli-avanza-xenia', 'Filter oli original Denso DXE-1001. Cocok untuk Avanza, Xenia, Rush, Terios, Gran Max, Luxio.', 35000, 100, 300, 'Denso', 'Avanza, Xenia, Rush, Terios', 'Baru', 'filter_oli_avanza.jpg', 50),
(3, 6, 'Kampas Rem Depan Brake Pad Honda Jazz RS', 'kampas-rem-depan-honda-jazz-rs', 'Kampas rem depan set (kiri kanan) untuk Honda Jazz RS GE8 / GK5. Produk aftermarket kualitas premium Nissin.', 250000, 20, 1000, 'Nissin', 'Honda Jazz RS', 'Baru', 'kampas_rem_jazz.jpg', 5),
(4, 3, 'Shockbreaker Belakang Kayaba Excel-G Innova', 'shockbreaker-belakang-kayaba-innova', 'Shock absorber belakang Kayaba Excel-G Gas untuk Toyota Kijang Innova. Harga per set (2 pcs). Lebih stabil dan nyaman.', 650000, 10, 5000, 'Kayaba (KYB)', 'Toyota Kijang Innova', 'Baru', 'shock_kyb_innova.jpg', 2),
(5, 4, 'Aki Mobil GS Astra MF NS60', 'aki-mobil-gs-astra-mf-ns60', 'Aki kering Maintenance Free (MF) GS Astra NS60 45Ah. Cocok untuk Avanza, Xenia, Rush, Terios, Starlet, Taruna.', 850000, 15, 12000, 'GS Astra', 'Avanza, Xenia, Rush', 'Baru', 'aki_gs_ns60.jpg', 8),
(6, 4, 'Busi NGK Iridium IX BKR6EIX', 'busi-ngk-iridium-ix', 'Busi NGK Iridium IX. Api lebih besar, tarikan enteng, irit BBM. Harga per satuan.', 95000, 100, 100, 'NGK', 'Universal', 'Baru', 'busi_ngk_iridium.jpg', 25),
(7, 1, 'Fan Belt Tali Kipas Avanza 1.3 Original', 'fan-belt-avanza-1.3', 'V-Ribbed Belt / Fan belt untuk alternator, water pump, AC Avanza 1.3 / Xenia 1.3. Original Daihatsu.', 110000, 30, 200, 'Daihatsu', 'Avanza 1.3, Xenia 1.3', 'Baru', 'fan_belt_avanza.jpg', 12),
(8, 7, 'Wiper Blade Bosch Advantage 20 + 16 Inch', 'wiper-bosch-advantage-20-16', 'Wiper frameless Bosch Advantage set ukuran 20" dan 16". Cocok untuk Avanza, Xenia, Jazz lama, Yaris lama.', 75000, 50, 500, 'Bosch', 'Universal (Hook Arm)', 'Baru', 'wiper_bosch.jpg', 40),
(9, 3, 'Tie Rod End 555 Jepang Suzuki Ertiga', 'tie-rod-end-555-ertiga', 'Tie Rod End set merk 555 Made in Japan untuk Suzuki Ertiga. Kualitas terjamin awet.', 350000, 15, 1500, '555 Japan', 'Suzuki Ertiga', 'Baru', 'tierod_555_ertiga.jpg', 3),
(10, 2, 'Minyak Rem Prestone DOT 3 Merah 300ml', 'minyak-rem-prestone-dot-3', 'Minyak rem Prestone DOT 3 Kemasan 300ml warna merah. Titik didih tinggi.', 30000, 60, 400, 'Prestone', 'Universal', 'Baru', 'prestone_dot3.jpg', 100);

-- Insert Ongkir Sample
INSERT INTO `ongkir` (`provinsi`, `kota`, `kecamatan`, `jne_reg`, `jnt`, `sicepat`, `cod`) VALUES
('DKI Jakarta', 'Jakarta Selatan', 'Tebet', 9000, 10000, 10000, 12000),
('DKI Jakarta', 'Jakarta Pusat', 'Menteng', 9000, 10000, 9000, 11000),
('Jawa Barat', 'Bandung', 'Coblong', 11000, 12000, 11000, 13000),
('Jawa Barat', 'Bekasi', 'Bekasi Barat', 10000, 11000, 10000, 12000),
('Banten', 'Tangerang', 'Cipondoh', 10000, 11000, 10000, 12000),
('Jawa Timur', 'Surabaya', 'Gubeng', 19000, 20000, 19000, 22000),
('Sumatera Utara', 'Medan', 'Medan Kota', 35000, 37000, 36000, 40000),
('Sumatera Utara', 'Medan', 'Medan Baru', 35000, 37000, 36000, 40000),
('Sumatera Utara', 'Binjai', 'Binjai Kota', 38000, 40000, 39000, 45000);

COMMIT;
