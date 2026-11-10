<?php 
declare(strict_types=1);  

// 1. LOGIKA (di atas, sebelum HTML) 
require __DIR__ . "/data.php"; 
require __DIR__ . "/functions.php";  

$daftarKategori = ["Semua", "Makanan", "Minuman", "Fashion", "Kerajinan"];  

// Ambil kategori dari URL, misal index.php?kategori=Minuman 
$kategoriDipilih = $_GET["kategori"] ?? "Semua"; 
if (!in_array($kategoriDipilih, $daftarKategori, true)) { 
    $kategoriDipilih = "Semua"; // tolak nilai yang tidak dikenal
} 

$produkTampil = filterKategori($daftarProduk, $kategoriDipilih);
$jmlHabis = count(array_filter($daftarProduk, fn($p) => $p["stok"] == 0));
$nilaiStok = hitungNilaiStok($daftarProduk);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= e($namaToko) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="kepala">
        <h1><?= e($namaToko) ?></h1>
        <p><?= count($daftarProduk) ?> produk &middot; <?= $jmlHabis ?> habis &middot; Nilai stok <?= formatRupiah($nilaiStok) ?></p>
        <div class="filter">
            <?php foreach ($daftarKategori as $kat): ?>
                <a href="?kategori=<?= urlencode($kat) ?>" class="<?= $kat === $kategoriDipilih ? 'aktif' : '' ?>"><?= e($kat) ?></a>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="grid">
        <?php if (empty($produkTampil)): ?>
            <p class="kosong">Tidak ada produk di kategori ini.</p>
        <?php else: ?>
            <?php foreach ($produkTampil as $produk): ?>
                <article class="kartu">
                    <div class="kategori"><?= e($produk["kategori"]) ?></div>
                    <h2><?= ($produk["nama"]) ?></h2>
                    <div class="harga"><?= formatRupiah($produk["harga"]) ?></div>
                    <p>Stok: <?= $produk["stok"] ?></p>
                    <?php $status = statusStok($produk["stok"]); ?>
                    <span class="<?= kelasBadge($status) ?>"><?= $status ?></span>
                    <?php $promo = labelPromo($produk["harga"]); ?>
                    <?php if ($promo !== ""): ?>        
                    <div class="promo"><?= e($promo) ?></div>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="kaki">&copy; <?= date('Y') ?> <?= e($namaToko) ?></div>
</body>
</html>