<?php
require '../vendor/autoload.php'; // Load PhpSpreadsheet
require_once '../koneksi.php'; // Koneksi ke database

use PhpOffice\PhpSpreadsheet\IOFactory;

// Fungsi untuk menghitung umur berdasarkan tanggal lahir
function calculateAge($birthdate) {
    $birthDate = new DateTime($birthdate);
    $today = new DateTime('today');
    $age = $today->diff($birthDate)->y; // Menghitung perbedaan tahun
    return $age;
}

// Fungsi untuk mengkonversi format tanggal dari Excel ke format yang diinginkan (Y-m-d)
function convertExcelDate($excelDate) {
    if (is_numeric($excelDate)) {
        // Jika Excel menyimpan tanggal dalam format serial number
        $unixDate = ($excelDate - 25569) * 86400; // Konversi dari serial number ke Unix timestamp
        return gmdate("Y-m-d", $unixDate);
    } else {
        // Jika sudah dalam format tanggal yang bisa dikenali PHP, biarkan saja
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

            // Mengambil sheet pertama saja secara eksplisit
            $sheet = $spreadsheet->getSheet(0); 
            $rows = $sheet->toArray();

            // Looping untuk setiap baris data dalam sheet pertama
            foreach ($rows as $index => $row) {
                // Lewatkan baris pertama jika itu header
                if ($index == 0) continue;

                // Pastikan jumlah kolom sesuai dengan tabel Anda
                if (count($row) < 16) { // Sesuaikan jumlah kolom berdasarkan tabel Anda
                    echo "Jumlah kolom tidak sesuai di baris " . ($index + 1);
                    exit;
                }

                // Mapping data sesuai dengan kolom di tabel
                $ba = $row[0] ?? '';
                $ba_cabang = $row[1] ?? '';
                $region = $row[2] ?? '';
                $cabang = $row[3] ?? '';
                $nama_lengkap = $row[4] ?? '';
                $status = $row[5] ?? '';
                $nik = $row[6] ?? '';
                $no_jamsostek = $row[7] ?? '';
                $no_ktp = $row[8] ?? '';

                // Konversi tanggal lahir ke format Y-m-d
                $tanggal_lahir = !empty($row[9]) ? convertExcelDate($row[9]) : '';
                $nama_ibu_kandung = $row[10] ?? '';
                $trainee_sejak = !empty($row[11]) ? convertExcelDate($row[11]) : '';
                $posisi = $row[12] ?? '';
                $no_hp = $row[13] ?? '';
                $jenis_kelamin = $row[14] ?? '';

                // Hitung umur berdasarkan tanggal lahir
                $umur = calculateAge($tanggal_lahir);

                // Cek apakah nama lengkap sudah ada di database
                $stmt_check = $conn->prepare("SELECT COUNT(*) FROM karyawan_magang WHERE nama_lengkap = ?");
                $stmt_check->bind_param("s", $nama_lengkap);
                $stmt_check->execute();
                $stmt_check->bind_result($count);
                $stmt_check->fetch();
                $stmt_check->close();

                // Jika nama sudah ada, lewati baris ini
                if ($count > 0) {
                    echo "Data dengan nama '$nama_lengkap' sudah ada. Baris ini dilewati.<br>";
                    continue; // Lewati ke iterasi berikutnya
                }

               
                // Query untuk memasukkan data ke dalam tabel `karyawan_magang`
                $stmt = $conn->prepare("INSERT INTO karyawan_magang (ba, ba_cabang, region, cabang, nama_lengkap, status, nik, no_jamsostek, no_ktp, tanggal_lahir, nama_ibu_kandung, trainee_sejak, posisi, no_hp, jenis_kelamin, umur) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssssssssssss", $ba, $ba_cabang, $region, $cabang, $nama_lengkap, $status, $nik, $no_jamsostek, $no_ktp, $tanggal_lahir, $nama_ibu_kandung, $trainee_sejak, $posisi, $no_hp, $jenis_kelamin, $umur);
                $stmt->execute();

                // Pastikan untuk memeriksa apakah ada kesalahan saat memasukkan data
                if ($stmt->error) {
                    echo "Error inserting data for '$nama_lengkap': " . $stmt->error . "<br>";
                } else {
                    echo "Data untuk '$nama_lengkap' berhasil diimpor.<br>";
                }
            }

            // Setelah selesai mengimport, redirect kembali ke home.php dengan alert
            echo "<script>
                    alert('Data berhasil diimport dari file: $nama_file');
                    window.location.href = 'data_trainee.php';
                  </script>";
        } catch (Exception $e) {
            echo "Gagal membaca file: " . $e->getMessage();
        }
    } else {
        echo "Harap unggah file Excel.";
    }
}
?>