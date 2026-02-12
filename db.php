<?php
// db.php
try {
    $pdo = new PDO('sqlite:data.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Update struktur tabel dengan kolom baru
    $query = "CREATE TABLE IF NOT EXISTS parkir (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nama TEXT NOT NULL,
        handphone TEXT,
        nomor_polisi TEXT NOT NULL,
        nomor_polisi_2 TEXT, 
        keterangan TEXT,
        tanggal_bayar DATE,
        tanggal_masuk DATE,
        status TEXT
    )";
    
    $pdo->exec($query);

} catch (PDOException $e) {
    echo "Koneksi Gagal: " . $e->getMessage();
    exit;
}
?>