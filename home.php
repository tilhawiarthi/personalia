<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Personalia</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        header {
            background-color: #007bff;
            color: #fff;
            padding: 20px 0;
            text-align: center;
        }
        nav {
            display: flex;
            justify-content: right;
            background-color: #0056b3;
            padding: 10px 0;
        }
        nav a {
            color: #fff;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
            font-size: 20px;
        }
        nav a:hover {
            text-decoration: underline;
        }
        
        .container {
    max-width: 1200px;
    margin: 20px auto;
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    justify-content: center;
}

.card {
    background: #fff;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
    width: 45%;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    text-align: center;
    cursor: pointer; /* Menambahkan cursor pointer agar menunjukkan klik */
    transition: transform 0.3s, box-shadow 0.3s; /* Menambahkan transisi untuk animasi 3D */
}

.card-content {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.card img {
    width: 150px;  /* Mengatur ukuran gambar lebih kecil */
    height: 150px;  /* Mengatur tinggi gambar lebih kecil */
    margin-bottom: 10px;
}

.card p {
    font-size: 16px;
    color: #333;
    font-weight: bold;
    margin: 0;
}

.card:hover {
    transform: translateY(-10px) rotateY(10deg); /* Efek 3D - gerakan vertikal dan rotasi */
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2); /* Efek bayangan lebih dalam */
}

.card:hover .card-content {
    transform: translateZ(20px); /* Menambahkan efek kedalaman pada konten */
}

.card:active {
    transform: translateY(4px); /* Efek saat diklik, memberikan kesan kontainer ditekan */
}


        footer {
            text-align: center;
            padding: 15px 0;
            
            color: #000;
            position: relative;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>

<header>
    <h1>Selamat Datang di Web Personalia</h1>
    <p>Kelola data dengan mudah.</p>
</header>

<nav>
    <a href="logout.php">Logout</a>
</nav>

<div class="container">
    <!-- Form Absensi -->
    <div class="card">
        <a href="absen/absensi.php" class="menu-item">
            <div class="card-content">
                <img src="img/icon.png" alt="Pengisian Form">
                <p>Absensi</p>
            </div>
        </a>
    </div>

    <!-- Form Data Trainee -->
    <div class="card">
        <a href="data-trainee/data_trainee.php" class="menu-item">
            <div class="card-content">
                <img src="img/1234.png" alt="Pengisian Form">
                <p>Data Trainee</p>
            </div>
        </a>
    </div>

    <!-- Form Data OS -->
    <div class="card">
        <a href="data-os/data_os.php" class="menu-item">
            <div class="card-content">
                <img src="img/1234.png" alt="Pengisian Form">
                <p>Data OS</p>
            </div>
        </a>
    </div>
</div>


<footer class="footer">
    <p>&copy; <?= date("Y") ?> All rights reserved</p>
</footer>

</body>
</html>
