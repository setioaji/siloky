<?php
// dummy.php
require 'db.php';

// --- DATASET INDONESIA --- //
$nama_depan = [
    'Budi', 'Siti', 'Agus', 'Dewi', 'Rina', 'Joko', 'Sri', 'Eko', 'Wati', 'Hendra',
    'Yuni', 'Dedi', 'Lestari', 'Iwan', 'Nur', 'Rizky', 'Putri', 'Adi', 'Ratna', 'Bayu',
    'Mega', 'Fajar', 'Indah', 'Bambang', 'Sari', 'Rudi', 'Dian', 'Andi', 'Maya', 'Tono'
];

$nama_belakang = [
    'Santoso', 'Wijaya', 'Saputra', 'Wibowo', 'Kusuma', 'Lestari', 'Hidayat', 'Pratama',
    'Susanto', 'Mulyani', 'Setiawan', 'Kurniawan', 'Permata', 'Nugroho', 'Wahyuni',
    'Subagyo', 'Pertiwi', 'Irawan', 'Cahyono', 'Utami', 'Anwar', 'Suharto', 'Yuliana',
    'Firmansyah', 'Sulaeman', 'Hartono', 'Purnomo', 'Rahmawati', 'Sugiarto', 'Maryati'
];

$kode_plat = ['B', 'D', 'L', 'N', 'AB', 'AD', 'F', 'H', 'Z', 'T'];

$keterangan_list = [
    'Tamu Bulanan', 'Member VIP', 'Karyawan', 'Dosen', 'Mahasiswa', 
    'Titip 2 Hari', 'Parkir Inap', '', '', '' // Sengaja ada kosong
];

// --- FUNGSI GENERATOR --- //
function acakNomorPolisi($kode_plat) {
    $kode = $kode_plat[array_rand($kode_plat)];
    $angka = rand(1000, 9999);
    
    // Generate 2-3 huruf belakang acak
    $belakang = '';
    $jumlah_huruf = rand(2, 3);
    for ($i = 0; $i < $jumlah_huruf; $i++) {
        $belakang .= chr(rand(65, 90)); // ASCII A-Z
    }
    
    return "$kode $angka $belakang";
}

function acakHP() {
    $provider = ['0812', '0813', '0857', '0856', '0878', '0896', '0821'];
    $prefix = $provider[array_rand($provider)];
    $nomor = '';
    for ($i = 0; $i < 8; $i++) {
        $nomor .= rand(0, 9);
    }
    return $prefix . $nomor;
}

// --- PROSES INSERT DATA --- //
try {
    // Reset data lama (opsional, uncomment jika ingin menghapus data lama dulu)
    // $pdo->exec("DELETE FROM parkir");
    // $pdo->exec("DELETE FROM sqlite_sequence WHERE name='parkir'"); // Reset Auto Increment

    $sql = "INSERT INTO parkir (nama, handphone, nomor_polisi, nomor_polisi_2, keterangan, tanggal_masuk, tanggal_bayar, status) 
            VALUES (:nama, :hp, :nopol1, :nopol2, :ket, :tmasuk, :tbayar, :status)";
    
    $stmt = $pdo->prepare($sql);
    
    echo "<h3>Sedang membuat data dummy...</h3>";
    echo "<ul>";

    for ($i = 1; $i <= 30; $i++) {
        // 1. Generate Nama
        $nama = $nama_depan[array_rand($nama_depan)] . ' ' . $nama_belakang[array_rand($nama_belakang)];
        
        // 2. Generate HP & Nopol
        $hp = acakHP();
        $nopol1 = acakNomorPolisi($kode_plat);
        
        // 3. Nopol 2 (30% kemungkinan ada)
        $nopol2 = (rand(1, 100) <= 30) ? acakNomorPolisi($kode_plat) : '';
        
        // 4. Keterangan
        $ket = $keterangan_list[array_rand($keterangan_list)];
        
        // 5. Tanggal & Status
        // Generate tanggal masuk antara 1-60 hari yang lalu
        $hari_lalu = rand(1, 60);
        $tgl_masuk = date('Y-m-d', strtotime("-$hari_lalu days"));
        
        // Tentukan Status secara acak
        $is_lunas = (rand(0, 1) == 1); 
        $status = $is_lunas ? 'Lunas' : 'Belum Bayar';
        
        // Jika lunas, tanggal bayar = tanggal masuk + 0 s/d 5 hari
        $tgl_bayar = null;
        if ($is_lunas) {
            $jeda_bayar = rand(0, 5);
            $tgl_bayar = date('Y-m-d', strtotime("$tgl_masuk +$jeda_bayar days"));
            
            // Penjagaan agar tgl_bayar tidak melebihi hari ini
            if ($tgl_bayar > date('Y-m-d')) {
                $tgl_bayar = date('Y-m-d');
            }
        }

        // Eksekusi
        $stmt->execute([
            'nama' => $nama,
            'hp' => $hp,
            'nopol1' => $nopol1,
            'nopol2' => $nopol2,
            'ket' => $ket,
            'tmasuk' => $tgl_masuk,
            'tbayar' => $tgl_bayar,
            'status' => $status
        ]);

        echo "<li>Data ke-$i: <b>$nama</b> ($status) ditambahkan.</li>";
    }

    echo "</ul>";
    echo "<h2 style='color:green'>BERHASIL! 30 Data Dummy Telah Ditambahkan.</h2>";
    echo "<a href='index.php'>Kembali ke Halaman Utama</a>";

} catch (PDOException $e) {
    echo "Gagal: " . $e->getMessage();
}