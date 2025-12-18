# MOTTODA MOTOR - E-Commerce Sparepart Mobil

Project ini adalah sistem e-commerce lengkap untuk penjualan sparepart mobil, dibangun menggunakan **PHP Native (Procedural)**, **MySQL**, dan **Bootstrap 5**.

## Fitur Utama
1.  **Katalog Produk**: Filtering berdasarkan Kategori, Harga, Merek, dan Tipe Mobil.
2.  **Search Engine Cerdas**: Menggunakan metode TF-IDF dengan preprocessing sederhana (Stemming & Stopword Removal) untuk hasil pencarian yang relevan.
3.  **Rekomendasi Produk (CBF)**: Menggunakan algoritma Jaccard/Cosine Similarity sederhana untuk merekomendasikan produk serupa di halaman detail.
4.  **Keranjang Belanja**: Sistem cart menggunakan Session.
5.  **Checkout & Ongkir Widget**: Perhitungan ongkir otomatis berdasarkan Kecamatan & Kota tujuan (Data Sample).
6.  **Admin Panel**:
    *   Dashboard Statistik (Chart.js)
    *   Manajemen Produk, Kategori, Ongkir
    *   Manajemen Pesanan (Update Status)
    *   Laporan Penjualan (Print PDF)

## Instalasi

### 1. Persiapan Database
1.  Buka phpMyAdmin.
2.  Buat database baru bernama `mottoda_motor`.
3.  Import file `sql/schema.sql` ke dalam database tersebut.

### 2. Konfigurasi Project
1.  Copy folder project ini ke `htdocs` (misal: `C:/xampp/htdocs/e-commerce`).
2.  Buka file `inc/config.php` dan sesuaikan setting database jika perlu:
    ```php
    $db_host = 'localhost';
    $db_user = 'root';
    $db_pass = '';
    $db_name = 'mottoda_motor';
    $base_url = 'http://localhost/e-commerce'; // Sesuaikan folder
    ```
3.  Pastikan modul `mysqli` aktif di PHP Anda.

## Akses Aplikasi

### User (Frontend)
*   **URL**: `http://localhost/e-commerce/pages/home.php`
*   Register akun baru untuk mencoba fitur checkout.

### Admin (Backend)
*   **URL**: `http://localhost/e-commerce/admin/login.php`
*   **Username**: `admin`
*   **Password**: `password` (Default)

## Catatan Teknis
*   **Search Engine**: Implementasi TF-IDF terdapat di `inc/functions.php` fungsi `search_products()`.
*   **Rekomendasi**: Implementasi Cosine Similarity di `inc/functions.php` fungsi `get_cosine_similarity()`.
*   **PDF Report**: Menggunakan fitur `window.print()` browser untuk kemudahan tanpa dependensi composer, namun logic query siap untuk library PDF.

---
**MOTTODA MOTOR** - Solusi Sparepart Mobil Terpercaya.
