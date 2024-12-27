<?php
require '../vendor/autoload.php'; // Load PhpSpreadsheet
require_once '../koneksi.php'; // Koneksi ke database

use PhpOffice\PhpSpreadsheet\IOFactory;

// Fungsi untuk menghitung umur berdasarkan tanggal lahir
function calculateAge($birthdate) {
    $birthDate = new DateTime($birthdate);
    $today = new DateTime('today');
    return $today->diff($birthDate)->y; // Menghitung perbedaan tahun
}

// Fungsi untuk mengkonversi format tanggal dari Excel ke format Y-m-d
function convertExcelDate($excelDate) {
    if (is_numeric($excelDate)) {
        $unixDate = ($excelDate - 25569) * 86400;
        return gmdate("Y-m-d", $unixDate);
    } else {
        return date("Y-m-d", strtotime($excelDate));
    }
}

if (isset($_POST['import'])) {
    $file_excel = $_FILES['file_excel']['tmp_name'];
    $nama_file = $_FILES['file_excel']['name']; // Ambil nama file yang diupload

    if ($file_excel) {
        try {
            // Load file Excel
            $spreadsheet = IOFactory::load($file_excel);

            // Mengambil sheet pertama saja
            $sheet = $spreadsheet->getSheet(0);
            $rows = $sheet->toArray();

            // Looping untuk setiap baris data dalam sheet
            foreach ($rows as $index => $row) {
                // Lewatkan baris pertama jika itu header
                if ($index == 0) continue;

                // Pastikan jumlah kolom sesuai dengan tabel
                if (count($row) < 11) { // Excel harus memiliki 11 kolom
                    echo "Jumlah kolom tidak sesuai di baris " . ($index + 1);
                    exit;
                }

                // Mapping data sesuai dengan kolom di tabel
                $ba = $row[0] ?? '';
                $ba_cabang = $row[1] ?? '';
                $region = $row[2] ?? '';
                $cabang = $row[3] ?? '';
                $posisi = $row[4] ?? '';
                $nik = $row[5] ?? '';
                $nama = $row[6] ?? '';
                $alamat = $row[7] ?? '';
                $umur = $row[8] ?? 0; // Kolom umur langsung dari Excel
                $tanggal_lahir = !empty($row[9]) ? convertExcelDate($row[9]) : null;
                $jenis_kelamin = $row[10] ?? '';

                // Cek apakah data sudah ada berdasarkan NIK
                $stmt_check = $conn->prepare("SELECT COUNT(*) FROM os WHERE nik = ?");
                $stmt_check->bind_param("s", $nik);
                $stmt_check->execute();
                $stmt_check->bind_result($count);
                $stmt_check->fetch();
                $stmt_check->close();

                // Jika data sudah ada, lewati
                if ($count > 0) {
                    echo "Data dengan NIK '$nik' sudah ada. Baris ini dilewati.<br>";
                    continue;
                }

                // Masukkan data ke dalam tabel
                $stmt = $conn->prepare("INSERT INTO os (ba, ba_cabang, region, cabang, posisi, nik, nama, alamat, umur, tanggal_lahir, jenis_kelamin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssssssss", $ba, $ba_cabang, $region, $cabang, $posisi, $nik, $nama, $alamat, $umur, $tanggal_lahir, $jenis_kelamin);
                $stmt->execute();

                // Periksa kesalahan saat memasukkan data
                if ($stmt->error) {
                    echo "Error inserting data for '$nama': " . $stmt->error . "<br>";
                } else {
                    echo "Data untuk '$nama' berhasil diimpor.<br>";
                }
            }

            echo "<script>
                    alert('Data berhasil diimport dari file: $nama_file');
                    window.location.href = 'download-dataos.php';
                  </script>";
        } catch (Exception $e) {
            echo "Gagal membaca file: " . $e->getMessage();
        }
    } else {
        echo "Harap unggah file Excel.";
    }
}
?>
