<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Personalia</title>

    <!-- Menyisipkan Bootstrap -->
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        header {
            background-image: url('img/header-bg.jpg'); /* Ganti dengan gambar background yang diinginkan */
            background-size: cover;
            color: white;
        }
        .jumbotron {
            background-color: rgba(0, 0, 0, 0.5); /* Tambahkan efek transparansi */
            padding: 2rem 1rem;
        }
        .jumbotron h1 {
            font-weight: bold;
        }
        .jumbotron p {
            font-size: 1.25rem;
        }
        .btn {
            font-size: 1.1rem;
            padding: 10px 20px;
            border-radius: 25px;
            transition: background-color 0.3s;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .btn-success:hover {
            background-color: #28a745;
        }
        .logo-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 30px 0;
        }
        .logo-container img {
            max-width: 80%;
            height: auto;
            max-height: 250px; /* Sesuaikan tinggi maksimum gambar */
        }
        .container {
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <div class="jumbotron jumbotron-fluid text-center">
            <div class="container">
                <h1>Selamat Datang</h1>
                <p>SALAM SATU HATI</p>
                <div class="mt-4">
                    <a href="login.php" class="btn btn-secondary">Masuk</a>
                    <a href="register.php" class="btn btn-success">Daftar</a>
                </div>
            </div>
        </div>
    </header>

    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-12 logo-container">
                    <img class="img img-responsive" src="img/logo1.png" alt="Logo" />
                </div>
            </div>
        </div>
    </section>
</body>
</html>
