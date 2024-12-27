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

// Fungsi untuk menghitung umur berdasarkan tanggal lahir
function calculateAge($birthdate) {
    $birthDate = new DateTime($birthdate);
    $today = new DateTime('today');
    $age = $today->diff($birthDate)->y;
    return $age;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data OS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            display: flex;
            min-height: 100vh;
        }

        .wrapper {
            display: flex;
            width: 100%;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            height: 100vh;
            position: fixed;
            padding: 20px;
            transition: all 0.3s ease;
        }

        .sidebar-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar-header h3 {
            color: #ecf0f1;
        }

        .sidebar-header .logo {
            width: 200px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
            background-image: transparent;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li a {
            color: #ecf0f1;
            text-decoration: none;
            font-size: 16px;
            display: flex;
            align-items: center;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .sidebar ul li a:hover {
            background-color: #34495e;
        }

        .sidebar ul li a i {
            margin-right: 10px;
        }

        /* Content Styles */
        .content {
            margin-left: 250px;
            width: calc(100% - 250px);
            padding: 20px;
            background-color: #ecf0f1;
            min-height: 100vh;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #2c3e50;
            color: #fff;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
        }

        .main-content {
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%; /* Set to 100% to match table width */
            overflow: auto; /* Add scroll if content exceeds */
        }
        .search-container { display: flex; justify-content: left; margin-bottom: 20px; }
        .search-container input[type="text"] { padding: 10px; font-size: 16px; border: 1px solid #ccc; width: 300px; border-radius: 4px; }
        .btn-search { padding: 10px 20px; font-size: 16px; background-color: #2c3e50; color: white; border: none; cursor: pointer; border-radius: 4px; margin-left: 10px; transition: background-color 0.3s; }
        .btn-search:hover { background-color: #0056b3; }
        .actions-container { display: flex; justify-content: center; align-items: center; gap: 10px; margin-bottom: 20px; }
        .actions-container .btn { padding: 8px 15px; font-size: 14px; border-radius: 4px; transition: background-color 0.3s; text-align: center; text-decoration: none; border: none; cursor: pointer; }
        .btn-primary { background-color: #2c3e50; color: white; }
        .btn-primary:hover { background-color: #0056b3; }
        .btn-danger { background-color: #dc3545; color: white; }
        .btn-danger:hover { background-color: #c82333; }
        .btn-success { background-color: #28a745; color: white; }
        .btn-success:hover { background-color: #218838; }

/* Styling untuk container agar tombol berbaris ke kanan */
.btn-container {
    display: flex;               /* Mengubah menjadi flexbox */
    justify-content: flex-start;    /* Menempatkan tombol di sisi kanan */
    gap: 10px;                    /* Memberi jarak antar tombol */
}

        .btn-danger { background-color: #dc3545; color: white;  }
        .btn-danger:hover { background-color: #c82333; }
        .btn-success { background-color: #28a745; color: white; }
        .btn-success:hover { background-color: #218838; } 

        table {
            width: 100%; /* Set to 100% to utilize full width */
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
            font-size: 14px;
        }

        table th {
            background-color: #2c3e50;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tr:hover {
            background-color: #e6f7ff;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            color: #7f8c8d;
        }

    </style>
</head>
<body>

    <div class="wrapper">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <h3>Admin</h3>
                <img src="../img/logo1.png" alt="Logo" class="logo">
            </div>
            <ul class="list-unstyled components">
            <li><a href="admin.php"><i class="fas fa-folder"></i> Dashboard</a></li>
            <li><a href="data-user.php"><i class="fas fa-user-shield"></i> Data User</a></li>
            <li><a href="data-absen.php"><i class="fas fa-calendar-alt"></i> Download Data Absensi</a></li>
            <li><a href="download-datatrainee.php"><i class="fas fa-download"></i> Download Data Trainee</a></li>
            <li><a href="download-datatos.php"><i class="fas fa-download"></i> Download Data OS</a></li>
            <li><a href="../index.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>

        <!-- Main content -->
        <div class="content">
            <main class="main-content">
                <h2>Data OS</h2><br>

                <!-- Form Pencarian -->
    <div class="search-container">
        <form action="" method="GET">
            <input type="text" name="search" placeholder="Cari nama karyawan..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn-search">Cari</button>
        </form><br> 
    </div>
                <!-- Button Group -->
                <div class="btn-container">
                    <a href="tambah-dataos.php" class="btn btn-primary">Tambah Data</a>
                    <form action="importOS.php" method="post" enctype="multipart/form-data">
    <input type="file" name="file_excel" accept=".xls, .xlsx">
    <button type="submit" name="import" class="btn btn-primary">Import Data</button>
</form>
                    <a href="download-dataos1.php" class="btn btn-primary">Download Data</a>
                </div><br>
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
        <?php 
        if ($result->num_rows > 0) { 
            $no = 1; // Inisialisasi nomor urut
            while ($row = $result->fetch_assoc()) { 
                $umur = calculateAge($row['tanggal_lahir']);
        ?>
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
                    <td><?= $umur ?></td>
                    <td><?= htmlspecialchars($row["tanggal_lahir"]) ?></td>
                    <td><?= htmlspecialchars($row["jenis_kelamin"]) ?></td>
                    <td>
                        <a href="edit-dataos.php?id=<?= $row["id"] ?>" class="btn btn-primary btn-sm">Edit</a>
                        <a href="hapus-dataos.php?id=<?= $row["id"] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                    </td>
                </tr>
        <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="13" class="text-center">Tidak ada data yang ditemukan.</td>
            </tr>
        <?php } ?>
    </tbody>
</table>

            </main>
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
