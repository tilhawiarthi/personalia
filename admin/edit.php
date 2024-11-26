<?php
require_once '../koneksi.php';


$id = $_GET["id"];

// Menyiapkan query untuk menghindari SQL Injection
$stmt = $conn->prepare("SELECT * FROM karyawan_magang WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $ba = $row["ba"];
    $ba_cabang = $row["ba_cabang"];
    $region = $row["region"];
    $cabang = $row["cabang"];
    $nama_lengkap = $row["nama_lengkap"];
    $status = $row["status"];
    $nik = $row["nik"]; // Ambil data yang ada di database
    $no_jamsostek = $row["no_jamsostek"]; // Ambil data yang ada di database
    $no_ktp = $row["no_ktp"];
    $tanggal_lahir = $row["tanggal_lahir"];
    $nama_ibu_kandung = $row["nama_ibu_kandung"];
    $trainee_sejak = $row["trainee_sejak"];
    $posisi = $row["posisi"];
    $no_hp = $row["no_hp"];
    $jenis_kelamin = $row["jenis_kelamin"];
    $umur = $row["umur"];
}

if (isset($_POST["submit"])) {
    $ba = $_POST["ba"];
    $ba_cabang = $_POST["ba_cabang"];
    $region = $_POST["region"];
    $cabang = $_POST["cabang"];
    $nama_lengkap = $_POST["nama_lengkap"];
    $status = isset($_POST["Status"]) ? $_POST["Status"] : $row["Status"]; 
    $nik = !empty($_POST["nik"]) ? $_POST["nik"] : null; // Set to null if not provided
    $no_jamsostek = !empty($_POST["no_jamsostek"]) ? $_POST["no_jamsostek"] : null; // Set to null if not provided
    $no_ktp = $_POST["no_ktp"];
    $tanggal_lahir = $_POST["tanggal_lahir"];
    $nama_ibu_kandung = $_POST["nama_ibu_kandung"];
    $trainee_sejak = $_POST["trainee_sejak"];
    $posisi = $_POST["posisi"];
    $no_hp = $_POST["no_hp"];
    $jenis_kelamin = $_POST["jenis_kelamin"];

    // Tentukan status berdasarkan tanggal_keluar
    // $today = date("d-m-Y");
    // $status = ($tanggal_keluar < $today) ? "Nonaktif" : "Aktif";

    // Menyiapkan query untuk update
    $stmt = $conn->prepare("UPDATE karyawan_magang SET ba = ?, ba_cabang = ?, region = ?, cabang = ?, nama_lengkap = ?, Status = ?, nik = ?, no_jamsostek = ?, no_ktp = ?, tanggal_lahir = ?, nama_ibu_kandung = ?, trainee_sejak = ?, posisi = ?, no_hp = ?, jenis_kelamin = ? WHERE id = ?");
    $stmt->bind_param("sssssssssssssssi", $ba, $ba_cabang, $region, $cabang, $nama_lengkap, $status, $nik, $no_jamsostek, $no_ktp, $tanggal_lahir, $nama_ibu_kandung, $trainee_sejak, $posisi, $no_hp, $jenis_kelamin, $id);

    if ($stmt->execute()) {
        // Setelah update, kembali ke halaman home untuk melihat perubahan
        header("Location: download-datatrainee.php");
        exit();
    } else {
        $message = "<div class='alert alert-danger'>Gagal mengupdate karyawan magang</div>";
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
    <label>Status:</label> 
    <select name="Status" required> 
        <option value="AKTIF" <?php if ($status == "AKTIF") echo "selected" ?>>AKTIF</option>
        <option value="TIDAK AKTIF" <?php if ($status == "TIDAK AKTIF") echo "selected" ?>>TIDAK AKTIF</option>
        <option value="TETAP" <?php if ($status == "TETAP") echo "selected" ?>>TETAP</option>
    </select><br><br> 
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
    <label>Posisi:</label>
    <select name="posisi" required>
        <option value="MEKANIK TRAINEE" <?php if ($posisi == "MEKANIK TRAINEE") echo "selected" ?>>MEKANIK TRAINEE</option>
        <option value="ADMIN TRAINEE" <?php if ($posisi == "ADMIN TRAINEE") echo "selected" ?>>ADMIN TRAINEE</option>
        <option value="SALESMAN TRAINEE" <?php if ($posisi == "SALESMAN TRAINEE") echo "selected" ?>>SALESMAN TRAINEE</option>
        <option value="COUNTER SALES TRAINEE" <?php if ($posisi == "COUNTER SALES TRAINEE") echo "selected" ?>>COUNTER SALES TRAINEE</option>
        <option value="PKL" <?php if ($posisi == "PKL") echo "selected" ?>>PKL</option>
        <option value="MAGANG STAR H23" <?php if ($posisi == "MAGANG STAR") echo "selected" ?>>MAGANG STAR H23</option>
    </select>
    <label>No HP:</label>
    <input type="text" name="no_hp" value="<?php echo htmlspecialchars($no_hp) ?>" required><br><br>
    <label>Jenis Kelamin:</label>
    <select name="jenis_kelamin" required>
        <option value="MALE" <?php if ($jenis_kelamin == "MALE") echo "selected" ?>>MALE</option>
        <option value="FEMALE" <?php if ($jenis_kelamin == "FEMALE") echo "selected" ?>>FEMALE</option>
    </select><br><br>
    <input type="submit" name="submit" value="Update"><br><br>
</form>

<?php if (isset($message)) echo $message; ?>
