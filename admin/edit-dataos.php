<?php
require_once '../koneksi.php';


$id = $_GET["id"];

// Menyiapkan query untuk menghindari SQL Injection
$stmt = $conn->prepare("SELECT * FROM os WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $ba = $row["ba"];
    $ba_cabang = $row["ba_cabang"];
    $region = $row["region"];
    $cabang = $row["cabang"];
    $posisi = $row["posisi"];
    $nik = $row["nik"];
    $nama = $row["nama"]; 
    $alamat = $row["alamat"]; 
    $umur = $row["umur"];
    $tanggal_lahir = $row["tanggal_lahir"];
    $jenis_kelamin = $row["jenis_kelamin"];
}

if (isset($_POST["submit"])) {
    $row = $result->fetch_assoc();
    $ba = $row["ba"];
    $ba_cabang = $row["ba_cabang"];
    $region = $row["region"];
    $cabang = $row["cabang"];
    $posisi = $row["posisi"];
    $nik = $row["nik"];
    $nama = $row["nama"]; // Ambil data yang ada di database
    $alamat = $row["alamat"]; // Ambil data yang ada di database
    $umur = $row["umur"];
    $tanggal_lahir = $row["tanggal_lahir"];
    $jenis_kelamin = $row["jenis_kelamin"];

    // Tentukan status berdasarkan tanggal_keluar
    // $today = date("d-m-Y");
    // $status = ($tanggal_keluar < $today) ? "Nonaktif" : "Aktif";

    // Menyiapkan query untuk update
    // $stmt = $conn->prepare("UPDATE os SET ba = ?, ba_cabang = ?, region = ?, cabang = ?, posisi = ?, nik = ?, nama = ?, alamat = ?, umur = ?, tanggal_lahir = ?, jenis_kelamin = ? WHERE id = ?");
    // $stmt->bind_param("sssssssssssi", $ba, $ba_cabang, $region, $cabang, $posisi, $nik, $nama, $alamat, $umur, $tanggal_lahir, $jenis_kelamin, $id);

    if ($stmt->execute()) {
        // Setelah update, kembali ke halaman home untuk melihat perubahan
        header("Location: download-dataos.php");
        exit();
    } else {
        $message = "<div class='alert alert-danger'>Gagal Mengupdate Data OS</div>";
    }
}

$stmt->close();
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

    .alert-danger {
        background-color: #f2dede;
        border-color: #ebccd1;
        color: #a94442;
    }
</style>

<form action="" method="post">
    <label>BA:</label>
    <input type="text" name="ba" value="<?php echo htmlspecialchars($ba) ?>" required><br><br>
    <label>BA Cabang:</label>
    <input type="text" name="ba_cabang" value="<?php echo htmlspecialchars($ba_cabang) ?>" required><br><br>
    <label>Region:</label>
    <input type="text" name="region" value="<?php echo htmlspecialchars($region) ?>" required><br><br>
    <label>Cabang:</label>
    <input type="text" name="cabang" value="<?php echo htmlspecialchars($cabang) ?>" required><br><br>
    <label>Nama Lengkap:</label>
    <input type="text" name="nama_lengkap" value="<?php echo htmlspecialchars($nama_lengkap) ?>" required><br><br>
    <label>NIK (HO yang isi):</label>
    <input type="text" name="nik" value="<?php echo htmlspecialchars($nik) ?>"><br><br>
    <label>No-Jamsostek:</label>
    <input type="text" name="no_jamsostek" value="<?php echo htmlspecialchars($no_jamsostek) ?>"><br><br>
    <label>No KTP:</label>
    <input type="text" name="no_ktp" value="<?php echo htmlspecialchars($no_ktp) ?>" required><br><br>
    <label>Tanggal Lahir:</label>
    <input type="date" name="tanggal_lahir" value="<?php echo htmlspecialchars($tanggal_lahir) ?>" required><br><br>
    <label>Nama Ibu Kandung:</label>
    <input type="text" name="nama_ibu_kandung" value="<?php echo htmlspecialchars($nama_ibu_kandung) ?>" required><br><br>
    <label>trainee Sejak:</label>
    <input type="date" name="trainee_sejak" value="<?php echo htmlspecialchars($trainee_sejak) ?>" required><br><br>
    <label>Jenis Kelamin:</label>
    <select name="jenis_kelamin" required>
        <option value="MALE" <?php if ($jenis_kelamin == "MALE") echo "selected" ?>>MALE</option>
        <option value="FEMALE" <?php if ($jenis_kelamin == "FEMALE") echo "selected" ?>>FEMALE</option>
    </select><br><br>
    <input type="submit" name="submit" value="Update"><br><br>
</form>

<?php if (isset($message)) echo $message; ?>
