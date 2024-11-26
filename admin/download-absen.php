<?php
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

// Koneksi ke database
$host = 'localhost';
$dbname = 'personalia';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Mendapatkan parameter bulan dan tahun dari URL
if (isset($_GET['month'])) {
    $selectedMonth = date('m', strtotime($_GET['month'])); // Mendapatkan bulan dari input (format YYYY-MM)
    $selectedYear = date('Y', strtotime($_GET['month']));  // Mendapatkan tahun dari input
} else {
    die("Bulan tidak dipilih.");
}

// Query untuk mengambil data absensi sesuai bulan dan tahun
$query = "SELECT nama, nik, tanggal, status FROM absensi 
          WHERE MONTH(tanggal) = :month 
          AND YEAR(tanggal) = :year
          ORDER BY nama, tanggal ASC";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':month', $selectedMonth, PDO::PARAM_INT);
$stmt->bindParam(':year', $selectedYear, PDO::PARAM_INT);
$stmt->execute();
$absensi_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Membuat file Excel baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Atur header
$header = ['Nama Karyawan', 'NIK'];
for ($day = 1; $day <= 31; $day++) {
    $header[] = str_pad($day, 2, '0', STR_PAD_LEFT) . '/' . $selectedMonth . '/' . $selectedYear;
}
$header[] = 'Total Tidak Hadir'; // Tambah kolom total tidak hadir
$sheet->fromArray($header, null, 'A1');

// Proses data absensi
$absensi_data = [];
foreach ($absensi_items as $item) {
    $nama = $item['nama'];
    $nik = $item['nik'];
    $tanggal = date('d', strtotime($item['tanggal'])); // Dapatkan tanggal
    $status = $item['status'];

    if (!isset($absensi_data[$nama])) {
        $absensi_data[$nama] = [
            'nama' => $nama,
            'nik' => $nik,
            'hadir' => array_fill(1, 31, ''), // Pastikan array mengisi semua tanggal dari 1 hingga 31
            'total_tidak_hadir' => 0 // Tambah untuk total tidak hadir
        ];
    }

    // Isi kehadiran per tanggal
    $absensi_data[$nama]['hadir'][(int)$tanggal] = $status; // Isi status di tanggal yang sesuai
    if ($status == 1) {
        $absensi_data[$nama]['total_hadir']++;
    } else if (in_array($status, ['Cuti', 'Sakit', 'Ijin'])) { // Hitung cuti, sakit, dan izin
        $absensi_data[$nama]['total_tidak_hadir']++;
    }
}

// Memasukkan data ke dalam Excel
$row = 2;
foreach ($absensi_data as $nama => $data) {
    $rowData = [$data['nama'], $data['nik']]; // Kolom A dan B
    for ($day = 1; $day <= 31; $day++) {
        $rowData[] = $data['hadir'][$day]; // Mulai dari kolom C
    }
    $rowData[] = $data['total_tidak_hadir']; // Tambahkan total tidak hadir ke dalam data
    $sheet->fromArray($rowData, null, 'A' . $row);
    $row++;
}

// Styling header
$sheet->getStyle('A1:AH1')->applyFromArray([
    'font' => ['bold' => true, 'color' => ['argb' => Color::COLOR_WHITE]],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF4CAF50']],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
]);

// Menambahkan border untuk semua sel
$sheet->getStyle('A1:AH' . ($row - 1))->applyFromArray([
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => 'FF000000'],
        ],
    ],
]);

// Atur lebar kolom untuk header
$sheet->getColumnDimension('A')->setWidth(20); // Nama Karyawan
$sheet->getColumnDimension('B')->setWidth(10); // NIK

// Atur lebar kolom untuk tanggal mulai dari kolom C
for ($day = 1; $day <= 31; $day++) {
    $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($day + 2); // Kolom ke-3 adalah untuk tanggal
    $sheet->getColumnDimension($columnLetter)->setWidth(12); // Lebar kolom untuk tanggal
}

// Atur lebar kolom untuk Total Tidak Hadir
$sheet->getColumnDimension('AH')->setWidth(20); // Total Tidak Hadir

// Atur posisi data di tengah kolom
for ($i = 1; $i <= $row; $i++) {
    for ($j = 1; $j <= 33; $j++) { // Kolom A hingga AJ
        $cellAddress = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($j) . $i;
        $sheet->getStyle($cellAddress)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }
}

// Atur warna merah dan kuning untuk kehadiran
for ($i = 2; $i < $row; $i++) {
    for ($j = 3; $j <= 33; $j++) { // Dimulai dari kolom C hingga kolom AG
        // Mengonversi nomor kolom ke huruf kolom
        $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($j);
        $cellAddress = $columnLetter . $i;
        
        $cellValue = $sheet->getCell($cellAddress)->getValue();
        
        if ($cellValue == 0) {
            $sheet->getStyle($cellAddress)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFFF0000'); // Warna merah untuk absen
        } elseif ($cellValue == 'Cuti' || $cellValue == 'Sakit' || $cellValue == 'Ijin') { // Cuti, Sakit, Izin
            $sheet->getStyle($cellAddress)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFFFFF00'); // Warna kuning untuk Cuti, Sakit, atau Izin
        }
    }
}

// Mengunduh file Excel
$writer = new Xlsx($spreadsheet);
$filename = 'absensi_' . $selectedMonth . '_' . $selectedYear . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit;
