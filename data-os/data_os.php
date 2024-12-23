<?php
require_once '../koneksi.php';

// Menangani pencarian
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $query = "SELECT * FROM os WHERE nama LIKE ? ORDER BY ba ASC";
    $stmt = $conn->prepare($query);
    $searchTerm = "%" . $search . "%";
    $stmt->bind_param("s", $searchTerm);
} else {
    $query = "SELECT * FROM os ORDER BY ba ASC";
    $stmt = $conn->prepare($query);
}
$stmt->execute();
$result = $stmt->get_result();

// Fungsi untuk menghitung umur
function calculateAge($birthdate) {
    $birthDate = new DateTime($birthdate);
    $today = new DateTime('today');
    return $today->diff($birthDate)->y;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data OS</title>
    <style>
        /* CSS seperti yang sebelumnya disediakan */
        .container { width: 100%; margin: 0 auto; padding: 20px; padding-bottom: 100px; }
        .logo { text-align: center; margin-bottom: 5px; }
        .logo img { width: 400px; }
        h2 { text-align: center; color: #333; font-size: 40px; margin-bottom: 50px; }
        .search-container { display: flex; justify-content: center; margin-bottom: 20px; }
        .search-container input[type="text"] { padding: 10px; font-size: 16px; border: 1px solid #ccc; width: 300px; border-radius: 4px; }
        .btn-search { padding: 10px 20px; font-size: 16px; background-color: #007bff; color: white; border: none; cursor: pointer; border-radius: 4px; margin-left: 10px; transition: background-color 0.3s; }
        .btn-search:hover { background-color: #0056b3; }
        .actions-container { display: flex; justify-content: center; align-items: center; gap: 10px; margin-bottom: 20px; }
        .actions-container .btn { padding: 8px 15px; font-size: 14px; border-radius: 4px; transition: background-color 0.3s; text-align: center; text-decoration: none; border: none; cursor: pointer; }
        .btn-primary { background-color: #007bff; color: white; }
        .btn-primary:hover { background-color: #0056b3; }
        .btn-danger { background-color: #dc3545; color: white; }
        .btn-danger:hover { background-color: #c82333; }
        .btn-success { background-color: #28a745; color: white; }
        .btn-success:hover { background-color: #218838; }
        .actions-container form { display: inline-block; }
        .alert { padding: 10px; background-color: #f8d7da; color: #721c24; border-radius: 4px; text-align: center; }
        footer { text-align: center; padding: 10px; background-color: #f1f1f1; position: fixed; left: 0; bottom: 0; width: 100%; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        table th, table td { border: 1px solid #ddd; padding: 15px; text-align: center; font-size: 14px; }
        table th { background-color: #007bff; color: white; }
        table tr:nth-child(even) { background-color: #f2f2f2; }
        table tr:hover { background-color: #e6f7ff; }
    </style>
</head>
<body>
<div class="container">

    <!-- Logo -->
    <div class="logo">
        <img src="../img/logo1.png" alt="Logo">
    </div>

    <!-- Judul Halaman -->
    <h2>Data OS</h2>

    <!-- Form Pencarian -->
    <div class="search-container">
        <form action="" method="GET">
            <input type="text" name="search" placeholder="Cari nama karyawan..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn-search">Cari</button>
        </form>
    </div>

    <!-- Tombol Tambah, Download, dan Import Data -->
    <div class="actions-container">
        <a href="tambah.php" class="btn btn-primary btn-sm">Tambah data</a>
        <form action="import.php" method="post" enctype="multipart/form-data">
            <input type="file" name="file_excel" accept=".xls, .xlsx">
            <button type="submit" name="import" class="btn btn-primary">Import Data</button>
        </form>
    </div>
     <!-- Tabel Data OS -->
     <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>BA</th>
                        <th>BA Cabang</th>
                        <th>Region</th>
                        <th>Cabang</th>
                        <th>Posisi</th>
                        <th>NIK</th>
                        <th>Nama Lengkap</th>
                        <th>Alamat</th>
                        <th>Umur</th>
                        <th>Tanggal Lahir</th>
                        <th>Jenis Kelamin</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row["ba"]) ?></td>
                                <td><?= htmlspecialchars($row["ba_cabang"]) ?></td>
                                <td><?= htmlspecialchars($row["region"]) ?></td>
                                <td><?= htmlspecialchars($row["cabang"]) ?></td>
                                <td><?= htmlspecialchars($row["posisi"]) ?></td>
                                <td><?= htmlspecialchars($row["nik"]) ?></td>
                                <td><?= htmlspecialchars($row["nama"]) ?></td>
                                <td><?= htmlspecialchars($row["alamat"]) ?></td>
                                <td><?= calculateAge($row["tanggal_lahir"]) ?></td>
                                <td><?= htmlspecialchars($row["tanggal_lahir"]) ?></td>
                                <td><?= htmlspecialchars($row["jenis_kelamin"]) ?></td>
                                <td>
                                    <a href="edit-dataos.php?id=<?= $row["id"] ?>" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="hapus-dataos.php?id=<?= $row["id"] ?>" class="btn btn-danger btn-sm">Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="13">Tidak ada data yang ditemukan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <footer class="footer">
                <p>&copy; <?= date("Y") ?> All rights reserved</p>
            </footer>
        </div>
    </div>
    <?php
    $stmt->close();
    $conn->close();
    ?>
</body>
</html>
