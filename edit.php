<?php
// edit.php
require 'db.php';

if (!isset($_GET['id'])) { header("Location: index.php"); exit; }
$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM parkir WHERE id = :id");
$stmt->execute(['id' => $id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$data) { echo "Data tidak ditemukan!"; exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "UPDATE parkir SET 
            nama = :nama, 
            handphone = :hp, 
            nomor_polisi = :nopol1, 
            nomor_polisi_2 = :nopol2, 
            keterangan = :ket,
            tanggal_masuk = :tmasuk, 
            tanggal_bayar = :tbayar, 
            status = :status 
            WHERE id = :id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'nama' => $_POST['nama'],
        'hp' => $_POST['handphone'],
        'nopol1' => $_POST['nomor_polisi'],
        'nopol2' => $_POST['nomor_polisi_2'],
        'ket' => $_POST['keterangan'],
        'tmasuk' => $_POST['tanggal_masuk'],
        'tbayar' => $_POST['tanggal_bayar'],
        'status' => $_POST['status'],
        'id' => $id
    ]);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-2xl bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Data Parkir</h2>
        
        <form method="POST">
             <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Pemilik</label>
                    <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Handphone</label>
                    <input type="number" placeholder="6285" name="handphone" value="<?= htmlspecialchars($data['handphone']) ?>" class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nomor Polisi 1</label>
                    <input type="text" name="nomor_polisi" value="<?= htmlspecialchars($data['nomor_polisi']) ?>" required class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nomor Polisi 2</label>
                    <input type="text" name="nomor_polisi_2" value="<?= htmlspecialchars($data['nomor_polisi_2']) ?>" class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Masuk</label>
                    <input type="date" name="tanggal_masuk" value="<?= htmlspecialchars($data['tanggal_masuk']) ?>" required class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Bayar</label>
                    <input type="date" name="tanggal_bayar" value="<?= htmlspecialchars($data['tanggal_bayar']) ?>" class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Keterangan</label>
                <textarea name="keterangan" rows="2" class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-blue-500 outline-none"><?= htmlspecialchars($data['keterangan']) ?></textarea>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                <select name="status" class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="Belum Bayar" <?= $data['status'] == 'Belum Bayar' ? 'selected' : '' ?>>Belum Bayar</option>
                    <option value="Lunas" <?= $data['status'] == 'Lunas' ? 'selected' : '' ?>>Lunas</option>
                </select>
            </div>

            <div class="flex items-center justify-between">
                <a href="index.php" class="text-gray-500 hover:text-gray-700">Kembali</a>
                <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">
                    Update Data
                </button>
            </div>
        </form>
    </div>
</body>
</html>