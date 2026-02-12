<?php
// create.php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "INSERT INTO parkir (nama, handphone, nomor_polisi, nomor_polisi_2, keterangan, tanggal_masuk, tanggal_bayar, status) 
            VALUES (:nama, :handphone, :nopol1, :nopol2, :ket, :tmasuk, :tbayar, :status)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'nama' => $_POST['nama'],
        'handphone' => $_POST['handphone'],
        'nopol1' => $_POST['nomor_polisi'],
        'nopol2' => $_POST['nomor_polisi_2'], // Kolom Baru
        'ket' => $_POST['keterangan'],         // Kolom Baru
        'tmasuk' => $_POST['tanggal_masuk'],
        'tbayar' => $_POST['tanggal_bayar'],
        'status' => $_POST['status']
    ]);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Data</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-2xl bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Tambah Data Parkir</h2>
        
        <form method="POST">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Pemilik</label>
                    <input type="text" name="nama" required class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Handphone</label>
                    <input type="number" placeholder="6285" name="handphone" class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nomor Polisi 1 (Utama)</label>
                    <input type="text" name="nomor_polisi" required class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nomor Polisi 2 (Opsional)</label>
                    <input type="text" name="nomor_polisi_2" class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Masuk</label>
                    <input type="date" name="tanggal_masuk" required class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Bayar</label>
                    <input type="date" name="tanggal_bayar" class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Keterangan</label>
                <textarea name="keterangan" rows="2" class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Catatan tambahan..."></textarea>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                <select name="status" class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="Belum Bayar">Belum Bayar</option>
                    <option value="Lunas">Lunas</option>
                </select>
            </div>

            <div class="flex items-center justify-between">
                <a href="index.php" class="text-gray-500 hover:text-gray-700">Kembali</a>
                <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</body>
</html>