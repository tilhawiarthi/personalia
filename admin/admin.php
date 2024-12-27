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
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Efek timbul */
    border-radius: 8px; /* Sudut membulat */
    transition: transform 0.3s ease, box-shadow 0.3s ease; /* Animasi */
}

.content:hover {
    transform: translateY(-5px); /* Elemen sedikit terangkat */
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3); /* Perbesar bayangan saat hover */
}

.header {
    text-align: center;
    padding: 10px 0;
    font-size: 1.5rem;
    background-color: #2c3e50;
    color: #fff;
    border-radius: 8px 8px 0 0; /* Sudut membulat untuk bagian atas */
}

.main-content h3 {
    margin-top: 20px;
    color: #2c3e50;
}

.dashboard-statistics {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    justify-content: center;
    margin-top: 20px;
}

.stat-box {
    text-align: center;
    padding: 20px 40px;
    background-color: #ffffff;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    text-decoration: none;
    color: #2c3e50;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.stat-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

.stat-box i {
    font-size: 2rem;
    color: #3498db;
}

.stat-box p {
    margin-top: 10px;
    font-size: 1rem;
    font-weight: bold;
}

.footer {
    text-align: center;
    padding: 50px 0;
    color:rgb(0, 0, 0);
    border-radius: 0 0 8px 8px; /* Sudut membulat untuk bagian bawah */
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
                <p>Data User</p>
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
