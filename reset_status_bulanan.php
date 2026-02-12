<?php
// reset_status.php

// 1. Ubah direktori kerja ke folder tempat file ini berada
// Ini PENTING agar script bisa menemukan file 'data.sqlite' saat dijalankan via Cron
chdir(__DIR__);

require 'db.php';

try {
    echo "--- Memulai Reset Status Bulanan ---\n";
    echo "Waktu: " . date('Y-m-d H:i:s') . "\n";

    // 2. Query Update: Ubah semua status jadi 'Belum Bayar'
    // Opsional: Anda bisa juga mereset tanggal_bayar jadi NULL jika diinginkan
    // $sql = "UPDATE parkir SET status = 'Belum Bayar', tanggal_bayar = NULL";
    
    $sql = "UPDATE parkir SET status = 'Belum Bayar'";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    $jumlahData = $stmt->rowCount();

    echo "Sukses! $jumlahData kendaraan statusnya telah diubah menjadi 'Belum Bayar'.\n";
    echo "--------------------------------------\n";

} catch (PDOException $e) {
    echo "ERROR: Gagal mereset data.\n";
    echo "Pesan: " . $e->getMessage() . "\n";
}
?>