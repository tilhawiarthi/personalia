<?php
require_once '../koneksi.php';


if (isset($_POST["submit"])) {
    $ba = $_POST["ba"];
    $ba_cabang = $_POST["ba_cabang"];
    $region = $_POST["region"];
    $cabang = $_POST["cabang"];
    $nama_lengkap = $_POST["nama_lengkap"];
    $status = $_POST["Status"];
    $nik = $_POST["nik"] ?: null; // Set to null if not provided
    $no_jamsostek = $_POST["no_jamsostek"] ?: null; // Set to null if not provided
    $no_ktp = $_POST["no_ktp"];
    $tanggal_lahir = $_POST["tanggal_lahir"];
    $nama_ibu_kandung = $_POST["nama_ibu_kandung"];
    $trainee_sejak = $_POST["trainee_sejak"]?? null;
    $posisi = $_POST["posisi"];
    $no_hp = $_POST["no_hp"];
    $jenis_kelamin = $_POST["jenis_kelamin"];

    // Query untuk menambahkan data
    $query = "INSERT INTO karyawan_magang (ba, ba_cabang, region, cabang, nama_lengkap, Status, nik, no_jamsostek, no_ktp, tanggal_lahir, nama_ibu_kandung, trainee_sejak, posisi, no_hp, jenis_kelamin) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        echo "<div class='alert alert-danger'>Gagal mempersiapkan statement: " . $conn->error . "</div>";
    } else {
        $stmt->bind_param("sssssssssssssss", $ba, $ba_cabang, $region, $cabang, $nama_lengkap, $status, $nik, $no_jamsostek, $no_ktp, $tanggal_lahir, $nama_ibu_kandung, $trainee_sejak, $posisi, $no_hp, $jenis_kelamin);
        
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Data berhasil ditambahkan</div>";
            header("Location: data_trainee.php");
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
    <label>Nama Lengkap:</label>
    <input type="text" name="nama_lengkap" required>
    <label>Status:</label>
    <select name="Status" required>
        <option value="AKTIF">AKTIF</option>
        <option value="TIDAK AKTIF">TIDAK AKTIF</option>
        <option value="TETAP">TETAP</option>
    </select>
    <label>NIK (HO yang isi):</label>
    <input type="text" name="nik"> <!-- Made optional -->
    <label>No Jamsostek:</label>
    <input type="text" name="no_jamsostek"> <!-- Made optional -->
    <label>No KTP:</label>
    <input type="text" name="no_ktp" required>
    <label>Tanggal Lahir:</label>
    <input type="date" name="tanggal_lahir" required>
    <label>Nama Ibu Kandung:</label>
    <input type="text" name="nama_ibu_kandung" required>
    <label>Trainee Sejak:</label>
    <input type="date" name="tanggal_masuk_training" required>
    <label>Posisi:</label>
    <select name="posisi" required>
        <option value="MEKANIK TRAINEE">MEKANIK TRAINEE</option>
        <option value="ADMIN">ADMIN TRAINEE</option>
        <option value="SALESMAN TRAINEE">SALESMAN TRAINEE</option>
        <option value="COUNTER SALES TRAINEE">COUNTER SALES TRAINEE</option>
        <option value="PKL">PKL</option>
        <option value="MAGANG STAR H23">MAGANG STAR H23</option>
    </select>
    <label>NO HP:</label>
    <input type="text" name="no_hp" required>
    <label>Jenis Kelamin:</label>
    <select name="jenis_kelamin" required>
        <option value="MALE">MALE</option>
        <option value="FEMALE">FEMALE</option>
    </select>
    <input type="submit" name="submit" value="Tambah">
</form>
