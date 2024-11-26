<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $conn = new mysqli("localhost", "root", "", "personalia");

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    $sql = "INSERT INTO user (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $password);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Jika registrasi berhasil, redirect ke halaman login
        header("Location: login.php");
        exit(); // Pastikan script berhenti setelah redirect
    } else {
        echo "Error: " . $stmt->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        /* Add some CSS improvements */
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
            width: 200px;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px -15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        button[type="submit"]:hover {
            background-color: #45a049;
        }
        .back-link {
            margin-top: 20px;
            display: block;
            color: #007BFF;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        /* Add some error handling styles */
        /* .error {
            color: #red;
            font-size: 12px;
            margin-bottom: 10px;
        } */
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="img\logo1.png" alt="Logo1"> <!-- Ganti path/to/logo.png dengan path gambar logo -->
        </div>
        <form method="POST" action="register.php">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required><br>
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>
            <?php if (isset($error)) { ?>
                <p class="error"><?= $error ?></p>
            <?php } ?>
            <button type="submit">Register</button>
        </form>
        <a class="back-link" href="index.php">Kembali ke Halaman Utama</a>
    </div>
</body>
</html>