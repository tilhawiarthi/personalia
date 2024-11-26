<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "personalia";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Role yang akan ditampilkan
$role = "user"; // Bisa diganti dengan "moderator" atau peran lainnya
$sql = "SELECT id, username, email FROM user WHERE role = '$role'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Daftar Pengguna - Role: <?php echo ucfirst($role); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .wrapper {
            display: flex;
            width: 100%;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            min-height: 100vh;
            padding: 20px;
            position: fixed;
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

        /* Main content */
        .main-content {
            margin-left: 270px;
            padding: 20px;
            flex: 1;
            background-color: #f4f4f4;
        }

        .table-container {
            max-width: 1000px;
            background-color: #fff;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 20px;
            margin: 20px auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
        }

        th {
            background-color: #2c3e50;
            color: white;
            font-weight: bold;
        }

        td {
            background-color: #fafafa;
        }

        tr:hover {
            background-color: #e1f5fe;
        }

        .no-users {
            text-align: center;
            font-size: 18px;
            color: #555;
            padding: 20px 0;
        }

        .button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #2c3e50;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            text-align: center;
        }

        .button:hover {
            background-color: #4cae4c;
        }

        footer {
            text-align: center;
            padding: 20px;
            margin-top: 20px;
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
            <li><a href="download-dataos.php"><i class="fas fa-download"></i> Download Data OS</a></li>
            <li><a href="../index.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>

    <!-- Main content -->
    <div class="main-content">
        <div class="table-container">
            <h2>Daftar <?php echo ucfirst($role); ?></h2>

            <?php
            // Cek apakah query berhasil
            if ($result->num_rows > 0) {
                // Tampilkan data dalam tabel
                echo "<table>";
                echo "<tr><th>Username</th><th>Email</th></tr>";
                
                // Looping data untuk setiap baris
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                // Pesan jika tidak ada pengguna ditemukan
                echo "<p class='no-users'>Tidak ada pengguna dengan role '$role' ditemukan.</p>";
            }

            // Tutup koneksi
            $conn->close();
            ?>

        </div>

        <footer>
            <p>&copy; <?= date("Y") ?> All rights reserved</p>
        </footer>
    </div>
</div>

</body>
</html>
