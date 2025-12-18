<?php
// inc/functions.php

require_once 'config.php';

// ==========================================================
// 1. HELPER FUNCTIONS
// ==========================================================

function base_url($path = '')
{
    global $base_url;
    return $base_url . '/' . ltrim($path, '/');
}

function redirect($path)
{
    header("Location: " . base_url($path));
    exit;
}

function clean_input($data)
{
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($data)));
}

function format_rupiah($angka)
{
    return "Rp " . number_format($angka, 0, ',', '.');
}

function flash_msg($name, $message = '', $type = 'success')
{
    if ($message) {
        $_SESSION['flash'][$name] = [
            'message' => $message,
            'type' => $type
        ];
    } else {
        if (isset($_SESSION['flash'][$name])) {
            $flash = $_SESSION['flash'][$name];
            unset($_SESSION['flash'][$name]);
            return '<div class="alert alert-' . $flash['type'] . ' alert-dismissible fade show" role="alert">
                        ' . $flash['message'] . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
        }
    }
    return '';
}

// ==========================================================
// 2. AUTH FUNCTIONS
// ==========================================================

function is_login()
{
    return isset($_SESSION['user_id']);
}

function is_admin_login()
{
    return isset($_SESSION['admin_id']);
}

function check_login()
{
    if (!is_login()) {
        redirect('pages/login.php');
    }
}

function check_admin_login()
{
    if (!is_admin_login()) {
        redirect('admin/index.php'); // Assuming admin login is at index.php
    }
}

function get_user($id)
{
    global $conn;
    $query = "SELECT * FROM users WHERE id = $id";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

// ==========================================================
// 3. PRODUCT & CATALOG FUNCTIONS
// ==========================================================

function get_categories()
{
    global $conn;
    $query = "SELECT * FROM categories ORDER BY nama_kategori ASC";
    return mysqli_query($conn, $query);
}

function get_products($filters = [])
{
    global $conn;
    $where_clauses = [];

    // Filter Kategori
    if (!empty($filters['category_slug'])) {
        $slug = clean_input($filters['category_slug']);
        $cat_query = mysqli_query($conn, "SELECT id FROM categories WHERE slug = '$slug'");
        if (mysqli_num_rows($cat_query) > 0) {
            $cat = mysqli_fetch_assoc($cat_query);
            $where_clauses[] = "category_id = " . $cat['id'];
        }
    }

    // Filter Harga
    if (!empty($filters['min_price'])) {
        $where_clauses[] = "harga >= " . (int) $filters['min_price'];
    }
    if (!empty($filters['max_price'])) {
        $where_clauses[] = "harga <= " . (int) $filters['max_price'];
    }

    // Filter Merek
    if (!empty($filters['brand'])) {
        $brand = clean_input($filters['brand']);
        $where_clauses[] = "merek LIKE '%$brand%'";
    }

    // Filter Tipe Mobil
    if (!empty($filters['car_type'])) {
        $car = clean_input($filters['car_type']);
        $where_clauses[] = "tipe_mobil LIKE '%$car%'";
    }

    // Search Query (Simpel LIKE sebelum TF-IDF full result)
    if (!empty($filters['search'])) {
        // Kita akan menggunakan TF-IDF untuk sorting nanti, tapi untuk filtering basic DB bisa pakai LIKE dulu
        // Atau biarkan kosong disini dan handle search logic terpisah.
        // Di fungsi ini kita return basic SQL result, search logic sebaiknya panggil get_search_results()
    }

    $sql = "SELECT p.*, c.nama_kategori FROM products p 
            JOIN categories c ON p.category_id = c.id";

    if (!empty($where_clauses)) {
        $sql .= " WHERE " . implode(" AND ", $where_clauses);
    }

    // Sorting
    if (!empty($filters['sort'])) {
        switch ($filters['sort']) {
            case 'price_asc':
                $sql .= " ORDER BY harga ASC";
                break;
            case 'price_desc':
                $sql .= " ORDER BY harga DESC";
                break;
            case 'newest':
                $sql .= " ORDER BY created_at DESC";
                break;
            default:
                $sql .= " ORDER BY created_at DESC";
        }
    } else {
        $sql .= " ORDER BY created_at DESC";
    }

    return mysqli_query($conn, $sql);
}

function get_product_by_slug($slug)
{
    global $conn;
    $slug = clean_input($slug);
    $query = "SELECT p.*, c.nama_kategori FROM products p 
              JOIN categories c ON p.category_id = c.id 
              WHERE p.slug = '$slug'";
    return mysqli_fetch_assoc(mysqli_query($conn, $query));
}

// ==========================================================
// 4. CART & CHECKOUT FUNCTIONS
// ==========================================================

function get_cart()
{
    return isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
}

function add_to_cart($product_id, $qty = 1)
{
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $qty;
    } else {
        $_SESSION['cart'][$product_id] = $qty;
    }
}

function update_cart($product_id, $qty)
{
    if ($qty <= 0) {
        unset($_SESSION['cart'][$product_id]);
    } else {
        $_SESSION['cart'][$product_id] = $qty;
    }
}

function get_cart_details()
{
    global $conn;
    $cart = get_cart();
    $items = [];
    $total = 0;

    if (empty($cart))
        return ['items' => [], 'total' => 0];

    $ids = implode(',', array_keys($cart));
    $query = "SELECT * FROM products WHERE id IN ($ids)";
    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $row['qty'] = $cart[$row['id']];
        $row['subtotal'] = $row['harga'] * $row['qty'];
        $items[] = $row;
        $total += $row['subtotal'];
    }

    return ['items' => $items, 'total' => $total];
}

// ==========================================================
// 5. SEARCH ENGINE (TF-IDF) & PREPROCESSING
// ==========================================================

// Preprocessing Sederhana (Mini Sastrawi)
function preprocess_text($text)
{
    $text = strtolower($text); // Case folding
    $text = preg_replace('/[^a-z0-9 ]/', '', $text); // Hapus simbol

    // Stopword Removal (Bahasa Indonesia)
    $stopwords = ['dan', 'yang', 'untuk', 'di', 'ke', 'dari', 'ini', 'itu', 'adalah', 'sebagai', 'dengan', 'pada', 'juga', 'mobil', 'sparepart'];
    $words = explode(' ', $text);
    $filtered_words = array_filter($words, function ($w) use ($stopwords) {
        return !in_array($w, $stopwords) && strlen($w) > 2;
    });

    // Stemming Sederhana (Rule Base)
    $stemmed_words = array_map(function ($word) {
        // Hapus partikel -lah, -kah, -pun
        if (preg_match('/(lah|kah|pun)$/', $word))
            $word = preg_replace('/(lah|kah|pun)$/', '', $word);
        // Hapus possessive pronoun -ku, -mu, -nya
        if (preg_match('/(ku|mu|nya)$/', $word))
            $word = preg_replace('/(ku|mu|nya)$/', '', $word);
        // Hapus shuffix -kan, -i, -an
        if (preg_match('/(kan|i|an)$/', $word))
            $word = preg_replace('/(kan|i|an)$/', '', $word);
        return $word;
    }, $filtered_words);

    return array_values($stemmed_words);
}

// Menghitung TF-IDF Score untuk Search
function search_products($nav_query)
{
    global $conn;

    // 1. Ambil semua produk (ID, Nama, Deskripsi, Merek, Tipe Mobil)
    $products_query = mysqli_query($conn, "SELECT id, nama_produk, deskripsi, merek, tipe_mobil FROM products");
    $documents = [];
    $all_products = [];

    while ($row = mysqli_fetch_assoc($products_query)) {
        $all_products[$row['id']] = $row;
        // Gabungkan semua teks relevan jadi satu dokumen
        $doc_text = $row['nama_produk'] . ' ' . $row['deskripsi'] . ' ' . $row['merek'] . ' ' . $row['tipe_mobil'];
        $documents[$row['id']] = preprocess_text($doc_text);
    }

    // 2. Preprocess Query
    $query_terms = preprocess_text($nav_query);
    if (empty($query_terms))
        return [];

    $scores = [];

    // Inverted Index sederhana & Hitung TF per dokumen
    // Kita hitung skor simpel: (Jumlah match term di dokumen)
    // Untuk TF-IDF asli butuh corpus besar, disini kita pakai simplifikasi Scoring

    foreach ($documents as $doc_id => $doc_terms) {
        $score = 0;
        $match_count = 0;

        foreach ($query_terms as $q_term) {
            // Hitung Term Frequency (TF) di dokumen ini
            $tf = 0;
            foreach ($doc_terms as $d_term) {
                if ($d_term == $q_term)
                    $tf++;
            }

            if ($tf > 0) {
                // Bobot lebih tinggi jika match di Judul/Merek (Asumsi kata awal di doc_terms adalah judul)
                // Kita sederhanakan: TF * 1
                $score += $tf;
                $match_count++;
            }
        }

        if ($score > 0) {
            $scores[$doc_id] = $score * ($match_count); // Boost jika match multiple terms
        }
    }

    // Sort by Score Descending
    arsort($scores);

    // Ambil detail produk berdasarkan hasil sort
    $results = [];
    foreach ($scores as $id => $s) {
        $query_res = mysqli_query($conn, "SELECT * FROM products WHERE id = $id");
        $prod = mysqli_fetch_assoc($query_res);
        $prod['search_score'] = $s;
        $results[] = $prod;
    }

    return $results;
}

// ==========================================================
// 6. CONTENT-BASED FILTERING (REKOMENDASI)
// ==========================================================

function get_cosine_similarity($product_id_target)
{
    global $conn;

    // 1. Ambil Produk Target
    $target_query = mysqli_query($conn, "SELECT id, nama_produk, deskripsi, category_id, tipe_mobil FROM products WHERE id = $product_id_target");
    $target_prod = mysqli_fetch_assoc($target_query);

    if (!$target_prod)
        return [];

    $target_text = $target_prod['nama_produk'] . ' ' . $target_prod['deskripsi'] . ' ' . $target_prod['tipe_mobil'];
    $target_vec = array_count_values(preprocess_text($target_text)); // Bag of Words Vector

    // 2. Ambil Kandidat Produk Lain (Minimal satu kategori atau tipe mobil sama utk optimasi)
    // Ambil semua produk biar akurat (dataset kecil ini ok)
    $candidates = mysqli_query($conn, "SELECT id, nama_produk, deskripsi, tipe_mobil FROM products WHERE id != $product_id_target");

    $similarities = [];

    while ($cand = mysqli_fetch_assoc($candidates)) {
        $cand_text = $cand['nama_produk'] . ' ' . $cand['deskripsi'] . ' ' . $cand['tipe_mobil'];
        $cand_vec = array_count_values(preprocess_text($cand_text));

        // Hitung Cosine Similarity
        // A . B / (|A| * |B|)

        $dot_product = 0;
        $mag_target = 0;
        $mag_cand = 0;

        // Union semua kata unik
        $all_words = array_unique(array_merge(array_keys($target_vec), array_keys($cand_vec)));

        foreach ($all_words as $word) {
            $val_target = isset($target_vec[$word]) ? $target_vec[$word] : 0;
            $val_cand = isset($cand_vec[$word]) ? $cand_vec[$word] : 0;

            $dot_product += ($val_target * $val_cand);
            $mag_target += ($val_target * $val_target);
            $mag_cand += ($val_cand * $val_cand);
        }

        $mag_target = sqrt($mag_target);
        $mag_cand = sqrt($mag_cand);

        if ($mag_target * $mag_cand > 0) {
            $similarity = $dot_product / ($mag_target * $mag_cand);
            $similarities[$cand['id']] = $similarity;
        }
    }

    // Sort Highest Similarity
    arsort($similarities);

    // Ambil Top 5
    $top_ids = array_slice(array_keys($similarities), 0, 5);
    if (empty($top_ids))
        return [];

    $ids_str = implode(',', $top_ids);
    $res = mysqli_query($conn, "SELECT * FROM products WHERE id IN ($ids_str)");

    $recommendations = [];
    while ($r = mysqli_fetch_assoc($res)) {
        $recommendations[] = $r;
    }

    return $recommendations;
}

// ==========================================================
// 7. ONGKIR & ALAMAT
// ==========================================================

function get_ongkir_options($kecamatan, $kota)
{
    global $conn;
    $kecamatan = clean_input($kecamatan);
    $kota = clean_input($kota);

    // Cari yang match kecamatan dan kota
    $query = "SELECT * FROM ongkir WHERE kecamatan LIKE '%$kecamatan%' AND kota LIKE '%$kota%' LIMIT 1";
    $res = mysqli_query($conn, $query);

    if (mysqli_num_rows($res) > 0) {
        return mysqli_fetch_assoc($res);
    }

    // Fallback kalau gak ketemu kecamatan, cari kota aja (Generalisasi)
    $query_kota = "SELECT * FROM ongkir WHERE kota LIKE '%$kota%' LIMIT 1";
    $res_kota = mysqli_query($conn, $query_kota);
    return mysqli_fetch_assoc($res_kota);
}

function get_user_address($user_id)
{
    global $conn;
    // Ambil default dulu
    $q = "SELECT * FROM alamat_user WHERE user_id = $user_id AND is_default = 1";
    $res = mysqli_query($conn, $q);
    if (mysqli_num_rows($res) > 0)
        return mysqli_fetch_assoc($res);

    // Kalau gak ada default, ambil sembarang
    $q2 = "SELECT * FROM alamat_user WHERE user_id = $user_id LIMIT 1";
    return mysqli_fetch_assoc(mysqli_query($conn, $q2));
}

?>