<?php
session_start();
if (!isset($_SESSION['username'])) {
    // Redirect ke halaman login jika user belum login
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Dashboard</title>
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
            background-color: #2c3e50;;
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
        }

        h3 {
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .dashboard-statistics {
            display: flex;
            justify-content: space-between;
        }

        .stat-box {
            background-color: #2c3e50;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            color: #fff;
            flex: 1;
            margin: 10px;
            transition: background-color 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-box i {
            font-size: 40px;
            margin-bottom: 10px;
        }

        .stat-box p {
            font-size: 18px;
            margin: 0;
        }

        .stat-box:hover {
            background-color: #757575;
        }

        /* Footer Styles */
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
                <li>
                    <a href="admin.php"><i class="fas fa-folder"></i> Dashboard</a>
                </li>
                <li>
                    <a href="data-user.php"><i class="fas fa-user-shield"></i> Data User</a>
                </li>
                <li>
                    <a href="data-absen.php"><i class="fas fa-calendar-alt"></i> Download Data Absensi</a>
                </li>
                <li>
                    <a href="download-datatrainee.php"><i class="fas fa-download"></i> Download Data Trainee</a>
                </li>
                <li>
                    <a href="download-dataos.php"><i class="fas fa-download"></i> Download Data OS</a>
                </li>
                <li>
                    <a href="../index.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
            </ul>
        </nav>

        <!-- Page Content -->
        <div class="content">
            <header class="header">
                <h2>Welcome, <?php echo $_SESSION['username']; ?></h2>
            </header>
            <main class="main-content">
                <h3>Admin Dashboard</h3>    
                <div class="dashboard-statistics">
                    <a href="data-absen.php" class="stat-box">
                        <i class="fas fa-download"></i>
                        <p>Download Data Absensi</p>
                    </a>
                    <a href="download-datatrainee.php" class="stat-box">
                        <i class="fas fa-download"></i>
                        <p>Download Data Trainee</p>
                    </a>
                    <a href="download-dataos.php" class="stat-box">
                        <i class="fas fa-download"></i>
                        <p>Download Data OS</p>
                    </a>
                    <a href="data-user.php" class="stat-box">
                        <i class="fas fa-user-shield"></i>
                        <p>Total User</p>
                    </a>
                </div>
            </main>
            <footer class="footer">
                <p>&copy; <?= date("Y") ?> All rights reserved</p>
            </footer>
        </div>
    </div>

</body>
</html>
