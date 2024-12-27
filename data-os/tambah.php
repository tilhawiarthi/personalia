<?php
require_once '../koneksi.php';

if (isset($_POST["submit"])) {
    $ba = $_POST["ba"];
    $ba_cabang = $_POST["ba_cabang"];
    $region = $_POST["region"];
    $cabang = $_POST["cabang"];
    $posisi = $_POST["posisi"];
    $nik = $_POST["nik"];
    $nama = $_POST["nama"] ?: null; // Set to null if not provided
    $alamat = $_POST["alamat"] ?: null; // Set to null if not provided
    $umur = $_POST["umur"];
    $tanggal_lahir = $_POST["tanggal_lahir"];
    $jenis_kelamin = $_POST["jenis_kelamin"];

    // Query untuk menambahkan data
    $query = "INSERT INTO os (ba, ba_cabang, region, cabang, posisi, nik, nama, alamat, umur, tanggal_lahir, jenis_kelamin) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        echo "<div class='alert alert-danger'>Gagal mempersiapkan statement: " . $conn->error . "</div>";
    } else {
        $stmt->bind_param("sssssssssss", $ba, $ba_cabang, $region, $cabang, $posisi, $nik, $nama, $alamat, $umur, $tanggal_lahir, $jenis_kelamin);
        
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Data berhasil ditambahkan</div>";
            header("Location: data_os.php");
            exit();
        } else {
            echo "<div class='alert alert-danger'>Gagal menambahkan data: " . $stmt->error . "</div>";
        }
        
        $stmt->close();
    }
}

$conn->close();
?>

<style>
    form {
        max-width: 500px;
        margin: 40px auto;
        padding: 20px;
        background-color: #fff;
        border: 1px solid #ddd;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        margin-bottom: 10px;
    }

    input[type="text"], input[type="email"], input[type="date"], select {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
    }

    input[type="submit"] {
        background-color: #337ab7;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: #23527c;
    }

    .alert {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #f0f0f0;
        color: #666;
        margin: 20px auto;
        width: 50%;
        text-align: center;
    }

    .alert-success {
        background-color: #dff0df;
        border-color: #d6e9c6;
        color: #3c763d;
    }

    .alert-danger {
        background-color: #f2dede;
        border-color: #ebccd1;
        color: #a94442;
    }

    .btn-success {
        background-color: #173F5F;
    }

    .btn {
        border: none;
        color: white;
        padding: 5px 10px;
        font-size: 14px;
        cursor: pointer;
    }
</style>

<form action="" method="post">
    <label>BA:</label>
    <input type="text" name="ba" required>
    <label>BA cabang:</label>
    <input type="text" name="ba_cabang" required>
    <label>Region:</label>
    <input type="text" name="region" required>
    <label>Cabang:</label>
    <input type="text" name="cabang" required>
    <label>Posisi:</label>
    <input type="text" name="posisi" required>
    <label>NIK:</label>
    <input type="text" name="nik">
    <label>Nama Lengkap:</label>
    <input type="text" name="nama"> <!-- Made optional -->
    <label>Alamat:</label>
    <input type="text" name="alamat" required>
    <label>Umur:</label>
    <input type="text" name="umur" required>
    <label>Tanggal Lahir:</label>
    <input type="date" name="tanggal_lahir" required>
    <label>Jenis Kelamin:</label>
    <select name="jenis_kelamin" required>
        <option value="MALE">MALE</option>
        <option value="FEMALE">FEMALE</option>
    </select>
    <input type="submit" name="submit" value="Tambah">
</form>