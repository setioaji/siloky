<?php
// index.php
require 'db.php';

// --- [BACKEND] API HANDLER UNTUK AJAX ---
// Bagian ini menangani request dari Javascript (tanpa reload halaman)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ajax_bayar') {
    header('Content-Type: application/json'); // Set header JSON
    
    try {
        $id = $_POST['id'];
        $tanggal_sekarang = date('Y-m-d'); 
        
        $sql = "UPDATE parkir SET status = 'Lunas', tanggal_bayar = :tgl WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            'tgl' => $tanggal_sekarang,
            'id' => $id
        ]);

        if ($result) {
            echo json_encode(['success' => true, 'tanggal' => $tanggal_sekarang]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal update database']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit; // Stop script di sini agar HTML tidak ikut terkirim
}

// --- [BACKEND] LOGIKA DELETE BIASA ---
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM parkir WHERE id = :id");
    $stmt->execute(['id' => $_GET['id']]);
    header("Location: index.php");
    exit;
}

// --- AMBIL DATA ---
$stmt = $pdo->query("SELECT * FROM parkir ORDER BY id DESC");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Parkir</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css">
    
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>

    <style>
        /* Custom style untuk merapikan DataTables dengan Tailwind */
        .dataTables_wrapper .dataTables_length select {
            padding-right: 2rem;
            width: auto;
        }
        .dataTables_wrapper .dataTables_filter input {
            padding: 0.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            margin-left: 0.5rem;
        }
    </style>
</head>
<body class="bg-gray-100 p-8 text-sm">

    <div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow-md">
        
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Daftar Parkir Kendaraan</h1>
            <a href="create.php" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow transition">
                <i class="fas fa-plus mr-2"></i>Tambah Data
            </a>
        </div>

        <div class="overflow-x-auto p-2">
            <table id="tabelParkir" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-200 text-gray-700 uppercase font-semibold text-xs">
                        <th class="py-3 px-4">Nama / Ket</th>
                        <th class="py-3 px-4">No. Polisi</th>
                        <th class="py-3 px-4">HP</th>
                        <th class="py-3 px-4 text-center">Tgl Masuk</th>
                        <th class="py-3 px-4 text-center">Tgl Bayar</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-center no-sort">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600">
                    <?php foreach ($data as $row): ?>
                    <tr id="row-<?= $row['id'] ?>" class="border-b border-gray-100 hover:bg-gray-50">
                        
                        <td class="py-3 px-4">
                            <div class="font-bold text-gray-800"><?= htmlspecialchars($row['nama']) ?></div>
                            <div class="text-xs text-gray-400"><?= htmlspecialchars($row['keterangan']) ?></div>
                        </td>
                        
                        <td class="py-3 px-4">
                            <div class="font-bold text-gray-700"><?= htmlspecialchars($row['nomor_polisi']) ?></div>
                            <?php if(!empty($row['nomor_polisi_2'])): ?>
                                <div class="text-xs text-gray-500"><?= htmlspecialchars($row['nomor_polisi_2']) ?></div>
                            <?php endif; ?>
                        </td>

                        <td class="py-3 px-4"><?= htmlspecialchars($row['handphone']) ?></td>
                        <td class="py-3 px-4 text-center"><?= htmlspecialchars($row['tanggal_masuk']) ?></td>
                        
                        <td class="py-3 px-4 text-center text-gray-500 tgl-bayar-cell">
                            <?= $row['tanggal_bayar'] ? htmlspecialchars($row['tanggal_bayar']) : '-' ?>
                        </td>

                        <td class="py-3 px-4 text-center status-cell">
                            <?php if($row['status'] == 'Lunas'): ?>
                                <span class="bg-green-100 text-green-700 border border-green-200 py-1 px-3 rounded-full text-xs font-semibold">Lunas</span>
                            <?php else: ?>
                                <span class="bg-red-100 text-red-700 border border-red-200 py-1 px-3 rounded-full text-xs font-semibold">Belum Bayar</span>
                            <?php endif; ?>
                        </td>

                        <td class="py-3 px-4 text-center">
                            <div class="flex item-center justify-center space-x-2 action-buttons">
                                
                                <?php if ($row['status'] != 'Lunas'): ?>
                                    <button 
                                        data-id="<?= $row['id'] ?>" 
                                        data-nama="<?= htmlspecialchars($row['nama']) ?>"
                                        class="btn-bayar bg-green-500 hover:bg-green-600 text-white py-1 px-3 rounded text-xs flex items-center shadow-sm transition">
                                        <i class="fas fa-money-bill-wave mr-1"></i> Bayar
                                    </button>
                                <?php else: ?>
                                    <span class="text-green-500 py-1 px-3 text-xs flex items-center cursor-default">
                                        <i class="fas fa-check-circle mr-1"></i> OK
                                    </span>
                                <?php endif; ?>

                                <a href="edit.php?id=<?= $row['id'] ?>" class="text-purple-600 hover:text-purple-800 p-1">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <a href="index.php?action=delete&id=<?= $row['id'] ?>" onclick="return confirm('Hapus data ini?')" class="text-red-600 hover:text-red-800 p-1">
                                    <i class="fas fa-trash-alt"></i>
                                </a>

                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // 1. Inisialisasi DataTables
            var table = $('#tabelParkir').DataTable({
                responsive: true,
                "language": {
                    "search": "Cari Nama / Nopol:",
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "paginate": {
                        "first": "Awal",
                        "last": "Akhir",
                        "next": "Lanjut",
                        "previous": "Mundur"
                    },
                    "zeroRecords": "Tidak ada data yang cocok ditemukan"
                },
                "columnDefs": [
                    { "orderable": false, "targets": 6 } // Matikan sorting di kolom Aksi (index 6)
                ]
            });

            // 2. Event Handler untuk Tombol Bayar (Menggunakan Delegation)
            // Kita pakai 'on' ke 'tbody' karena DataTables merender ulang DOM saat pagination/search
            $('#tabelParkir tbody').on('click', '.btn-bayar', function() {
                var btn = $(this);
                var id = btn.data('id');
                var nama = btn.data('nama');
                var row = btn.closest('tr'); // Ambil baris tr terkait

                if(!confirm('Konfirmasi pembayaran untuk ' + nama + '?')) {
                    return;
                }

                // Ubah tombol jadi loading (opsional, visual feedback)
                btn.html('<i class="fas fa-spinner fa-spin"></i> Proses...');
                btn.prop('disabled', true);

                // Kirim AJAX Request
                $.ajax({
                    url: 'index.php',
                    type: 'POST',
                    data: {
                        action: 'ajax_bayar',
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        if(response.success) {
                            // Update Tampilan Tanpa Reload
                            
                            // 1. Update Kolom Tanggal Bayar
                            row.find('.tgl-bayar-cell').text(response.tanggal);

                            // 2. Update Badge Status
                            row.find('.status-cell').html(
                                '<span class="bg-green-100 text-green-700 border border-green-200 py-1 px-3 rounded-full text-xs font-semibold">Lunas</span>'
                            );

                            // 3. Ganti Tombol Bayar dengan Icon OK
                            btn.replaceWith(
                                '<span class="text-green-500 py-1 px-3 text-xs flex items-center cursor-default"><i class="fas fa-check-circle mr-1"></i> OK</span>'
                            );

                        } else {
                            alert('Gagal: ' + response.message);
                            btn.html('<i class="fas fa-money-bill-wave mr-1"></i> Bayar');
                            btn.prop('disabled', false);
                        }
                    },
                    error: function() {
                        alert('Terjadi kesalahan koneksi.');
                        btn.html('<i class="fas fa-money-bill-wave mr-1"></i> Bayar');
                        btn.prop('disabled', false);
                    }
                });
            });
        });
    </script>

</body>
</html>