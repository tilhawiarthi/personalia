<?php
session_start(); // Mulai sesi untuk menyimpan data pengguna

$error_message = ""; // Variabel untuk menyimpan pesan error

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Koneksi ke database
    $conn = new mysqli("localhost", "root", "", "personalia");

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Query untuk mengambil data pengguna berdasarkan email
    $sql = "SELECT * FROM user WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verifikasi password
        if (password_verify($password, $row['password'])) {
            // Set session dengan data pengguna
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['role'] = $row['role']; // Menyimpan role pengguna (admin/user)

             // Daftar email admin
             $admin_emails = ['admin@gmail.com', 'tilhaniputu27@gmail.com']; // Tambahkan lebih banyak email admin sesuai kebutuhan

             // Cek apakah email termasuk dalam daftar admin
             if (in_array($email, $admin_emails)) {
                 // Jika email admin, redirect ke home.php
                 header("Location: admin/admin.php");
             } else {
                 // Jika bukan admin, redirect ke halaman user
                 header("Location: home.php");
             }
             exit(); // Pastikan script berhenti setelah redirect
         } else {
             $error_message = "Password salah!";
         }
     } else {
         $error_message = "Email tidak ditemukan!";
     }
 // Menutup statement dan koneksi
 $stmt->close();
 $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px;
        }
        .logo img {
            width: 150px;
            margin-bottom: 20px;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            margin-top: 10px;
        }
        button[type="submit"]:hover {
            background-color: #45a049;
        }
        .back-link {
            margin-top: 20px;
            display: block;
            color: #007BFF;
            text-decoration: none;
            font-size: 14px;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            margin-bottom: 15px;
            width: 100%;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (!empty($error_message)) : ?>
            <div class="error-message">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <div class="logo">
            <img src="img/logo1.png" alt="Logo"> <!-- Ganti dengan path gambar logo -->
        </div>
        <form method="POST" action="login.php">
            <input type="email" id="email" name="email" placeholder="Email" required><br>
            <input type="password" id="password" name="password" placeholder="Password" required><br><br>
            <button type="submit">Login</button>
        </form>
        <a class="back-link" href="index.php">Kembali ke Halaman Utama</a>
        <footer class="footer">
        <p>&copy; <?= date("Y") ?> All rights reserved</p>
    </footer>
    </div>
</body>
</html>
