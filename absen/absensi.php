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

// Proses pengajuan izin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $nik = $_POST['nik'];
    $status = $_POST['status'];  // Mengambil status dari form
    $tanggal = $_POST['tanggal']; // Tanggal izin yang diinput
    $selesai = $_POST['selesai']; // Tanggal izin yang diinput
    $keterangan = $_POST['keterangan'];

// Cek jika folder "uploads/" ada, jika tidak, buat foldernya
$targetDir = "uploads/"; // Direktori tujuan untuk file upload
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true); // Buat folder dengan izin penuh (read, write, execute)
}

    // Untuk menangani file upload (opsional)
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $foto = $_FILES['foto'];
        $fotoName = time() . "_" . basename($foto['name']);
        $targetDir = "uploads/"; // Pastikan folder ini ada dan dapat ditulis
        $targetFile = $targetDir . $fotoName;
        
        // Pindahkan file ke direktori yang diinginkan
        if (!move_uploaded_file($foto['tmp_name'], $targetFile)) {
            die("Upload gagal!");
        }
    } else {
        $fotoName = null; // Jika tidak ada foto, set sebagai null
    }

    // Query untuk menyimpan data ke database
    $sql = "INSERT INTO absensi (nama, nik, tanggal, selesai, status, keterangan, foto) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    $stmt->bind_param("sssssss", $nama, $nik, $tanggal, $selesai, $status, $keterangan, $fotoName);
    
    if ($stmt->execute()) {
        // Menampilkan alert pop-up menggunakan JavaScript setelah simpan berhasil
        echo "<script>
                alert('Data berhasil disimpan!');
                window.location.href = '../home.php'; // Mengarahkan kembali ke halaman form
              </script>";
    } else {
        echo "<script>
                alert('Error: " . $stmt->error . "');
              </script>";
    }
    
    // Tutup statement dan koneksi
    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Izin</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-size: 0.9rem;
        }
        input[type="text"], input[type="date"], select, input[type="file"], input[type="submit"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        input[type="text"]:focus, input[type="date"]:focus, select:focus, input[type="file"]:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.2);
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            cursor: pointer;
            border: none;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Formulir Izin</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-section">
                <label for="nama">Nama:</label>
                <input type="text" id="nama" name="nama" placeholder="Masukkan nama" required>
            </div>

            <div class="form-section">
                <label for="nik">NIK:</label>
                <input type="text" id="nik" name="nik" placeholder="Masukkan NIK" required>
            </div>

            <div class="form-section">
                <label for="status">status Izin:</label>
                <select id="status" name="status" required>
                    <option value="Ijin">Ijin</option>
                    <option value="Sakit">Sakit</option>
                    <option value="Cuti">Cuti</option>
                </select>
            </div>

            <div class="form-section">
                <label for="tanggal">Tanggal Izin:</label>
                <input type="date" id="tanggal" name="tanggal" required>
            </div>

            <div class="form-section">
                <label for="selesai">Sampai Tanggal (isi jika lebih dari 1 hari):</label>
                <input type="date" id="selesai" name="selesai" required>
            </div>

            <div class="form-section">
                <label for="keterangan">Keterangan:</label>
                <input type="text" id="keterangan" name="keterangan" placeholder="Keterangan (Opsional)" required>
            </div>

            <div class="form-section">
                <label for="foto">Unggah Bukti Foto (Opsional):</label>
                <input type="file" id="foto" name="foto" accept="image/*">
            </div>

            <input type="submit" value="Ajukan Izin">
        </form>
    </div>
</body>
</html>
