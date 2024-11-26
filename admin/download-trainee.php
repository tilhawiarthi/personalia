<?php
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

// Koneksi ke database
$host = 'localhost';
$dbname = 'personalia';
$username = 'root';  // Ganti sesuai dengan username database Anda
$password = '';  // Ganti sesuai dengan password database Anda

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Ambil data dari tabel karyawan_magang
$query = "SELECT 
            ba, ba_cabang, region, cabang, nama_lengkap, Status, nik, no_jamsostek, no_ktp, 
            tanggal_lahir, nama_ibu_kandung, trainee_sejak,
             posisi, no_hp, jenis_kelamin, umur
          FROM karyawan_magang";

$stmt = $pdo->prepare($query);
$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC); // Mendapatkan semua data dalam bentuk array

// Urutan yang diinginkan
$customOrder = [
    'H540', 'H541', 'H542', 'H543', 'H544', 'H545', 
    'H546', 'H547', 'H548', 'H549', 'H550', 'H551', 
    'H552', 'H553', 'H554', 'H555', 'H556', 'H557'
];

// Fungsi untuk mengurutkan ba_cabang sesuai urutan di $customOrder
usort($items, function ($a, $b) use ($customOrder) {
    $posA = array_search($a['ba_cabang'], $customOrder);
    $posB = array_search($b['ba_cabang'], $customOrder);
    
    // Jika ba_cabang tidak ada di urutan khusus, letakkan di akhir
    $posA = ($posA === false) ? PHP_INT_MAX : $posA;
    $posB = ($posB === false) ? PHP_INT_MAX : $posB;

    return $posA - $posB;
});


// Memeriksa apakah data ada
if (!empty($items)) {
    $spreadsheet = new Spreadsheet();
    
// Sheet pertama
$sheet1 = $spreadsheet->getActiveSheet();
$sheet1->setTitle('Data Karyawan');

// Set header untuk sheet pertama
$headers = [
    'BA', 'BA Cabang', 'Region', 'Cabang', 'Nama Lengkap', 'Status', 'NIK(HO yg isi)',
    'No-jamsostek', 'No KTP', 'Tanggal Lahir', 'Nama Ibu Kandung', 
    'Trainee Sejak', 'Posisi', 
    'No Hp', 'Jenis Kelamin', 'Umur'
];

$col = 'A';
foreach ($headers as $header) {
    $sheet1->setCellValue($col . '1', $header);
    $col++;
}

// Apply styles to header row
$sheet1->getStyle('A1:P1')->applyFromArray([
    'font' => ['bold' => true],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER // Perataan vertikal di tengah
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['argb' => 'FFFF00']
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN
        ]
    ]
]);

// Set column widths for header
$columnWidths = [
    'A' => 10, 'B' => 10, 'C' => 25, 'D' => 25, 'E' => 25, 'F' => 15,
    'G' => 20, 'H' => 25, 'I' => 25, 'J' => 25, 'K' => 25, 'L' => 25,
    'M' => 25, 'N' => 25, 'O' => 25, 'P' => 25,  
];

foreach ($columnWidths as $colID => $width) {
    $sheet1->getColumnDimension($colID)->setWidth($width);
}

// Add data to sheet pertama
$row = 2;
$umurCount = [
    '17' => 0,
    '18-25' => 0,
    '26-35' => 0,
    '36-45' => 0,
    '46-55' => 0,
    '56+' => 0,
];
$genderCount = [
    'Laki-laki' => 0,
    'Perempuan' => 0,
];

foreach ($items as $item) {
    $sheet1->setCellValue('A' . $row, $item['ba']);
    $sheet1->setCellValue('B' . $row, $item['ba_cabang']);
    $sheet1->setCellValue('C' . $row, $item['region']);
    $sheet1->setCellValue('D' . $row, $item['cabang']);
    $sheet1->setCellValue('E' . $row, $item['nama_lengkap']);
    $sheet1->setCellValue('F' . $row, $item['Status']);
    $sheet1->setCellValue('G' . $row, $item['nik']);
    $sheet1->setCellValue('H' . $row, $item['no_jamsostek']);
    $sheet1->setCellValue('I' . $row, $item['no_ktp']);
    $sheet1->setCellValue('J' . $row, date('d F Y', strtotime($item['tanggal_lahir'])));
    $sheet1->setCellValue('K' . $row, $item['nama_ibu_kandung']);
    $sheet1->setCellValue('L' . $row, date('d F Y', strtotime($item['trainee_sejak'])));
    $sheet1->setCellValue('M' . $row, $item['posisi']);
    $sheet1->setCellValue('N' . $row, $item['no_hp']);
    $sheet1->setCellValue('O' . $row, $item['jenis_kelamin']);

    // Menghitung umur dari tanggal lahir
    $tanggalLahir = new DateTime($item['tanggal_lahir']);
    $today = new DateTime();
    $umur = $today->diff($tanggalLahir)->y; // Menghitung tahun
    $sheet1->setCellValue('P' . $row, $umur); // Menyimpan umur ke kolom P

    // Apply alignment (center both horizontally and vertically) for the data rows
    $sheet1->getStyle('A' . $row . ':P' . $row)->applyFromArray([
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER // Perataan vertikal di tengah
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN
            ]
        ]
    ]);

    // Hitung jumlah umur
    if ($umur == 17) {
        $umurCount['17']++;
    } elseif ($umur >= 18 && $umur <= 25) {
        $umurCount['18-25']++;
    } elseif ($umur >= 26 && $umur <= 35) {
        $umurCount['26-35']++;
    } elseif ($umur >= 36 && $umur <= 45) {
        $umurCount['36-45']++;
    } elseif ($umur >= 46 && $umur <= 55) {
        $umurCount['46-55']++;
    } elseif ($umur >= 56) {
        $umurCount['56+']++;
    }

    // Hitung jumlah berdasarkan jenis kelamin
    if ($item['jenis_kelamin'] === 'MALE') {
        $genderCount['Laki-laki']++;
    } elseif ($item['jenis_kelamin'] === 'FEMALE') {
        $genderCount['Perempuan']++;
    }

    // Ubah warna kolom "Status" jika status adalah "Tidak Aktif"
    if ($item['Status'] === 'TIDAK AKTIF') {
        $sheet1->getStyle('F' . $row)->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF0000'], // Warna merah
            ],
            'font' => [
                'color' => ['argb' => 'FFFFFF'], // Teks warna putih agar kontras dengan background merah
            ]
        ]);
    }

    // Ubah warna kolom "Status" jika status adalah "Aktif"
    if ($item['Status'] === 'AKTIF') {
        $sheet1->getStyle('F' . $row)->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => '0000FF'], // Warna biru
            ],
            'font' => [
                'color' => ['argb' => 'FFFFFF'], // Teks warna putih agar kontras dengan background biru
            ]
        ]);
    }

    // Ubah warna kolom "Status" jika status adalah "Tetap"
    if ($item['Status'] === 'TETAP') {
        $sheet1->getStyle('F' . $row)->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => '006400'], // Warna hijau
            ],
            'font' => [
                'color' => ['argb' => 'FFFFFF'], // Teks warna putih agar kontras dengan background hijau
            ]
        ]);
    }

    $row++;
}

// Apply borders to the entire table
$sheet1->getStyle('A1:P' . ($row - 1))->applyFromArray([
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN
        ]
    ]
]);


    
    // Sheet kedua
$sheet2 = $spreadsheet->createSheet();
$sheet2->setTitle('Statistik Umur');

// Header untuk sheet kedua
$sheet2->setCellValue('A1', 'Umur');
$sheet2->setCellValue('B1', 'MALE');
$sheet2->setCellValue('C1', 'FEMALE');
$sheet2->setCellValue('D1', 'Total');

// Mengatur lebar kolom untuk header secara otomatis
$sheet2->getColumnDimension('A')->setAutoSize(true); // Umur
$sheet2->getColumnDimension('B')->setAutoSize(true); // Laki-Laki
$sheet2->getColumnDimension('C')->setAutoSize(true); // Perempuan
$sheet2->getColumnDimension('D')->setAutoSize(true); // Total

// Data untuk sheet kedua
$sheet2->setCellValue('A2', '17');
$sheet2->setCellValue('A3', '18-25');
$sheet2->setCellValue('A4', '26-35');
$sheet2->setCellValue('A5', '36-45');
$sheet2->setCellValue('A6', '46-55');
$sheet2->setCellValue('A7', '56+');
$sheet2->setCellValue('A8', 'Grand Total');

// Looping untuk mengisi data Laki-laki, Perempuan, dan Total
$row = 2;
$maleCount = [
    '17' => 0,
    '18-25' => 0,
    '26-35' => 0,
    '36-45' => 0,
    '46-55' => 0,
    '56+' => 0,
];
$femaleCount = [
    '17' => 0,
    '18-25' => 0,
    '26-35' => 0,
    '36-45' => 0,
    '46-55' => 0,
    '56+' => 0,
];
foreach ($items as $item) {
    $tanggalLahir = new DateTime($item['tanggal_lahir']);
    $today = new DateTime();
    $umur = $today->diff($tanggalLahir)->y; // Menghitung tahun
    if ($umur == 17) {
        if ($item['jenis_kelamin'] === 'MALE') {
            $maleCount['17']++;
        } elseif ($item['jenis_kelamin'] === 'FEMALE') {
            $femaleCount['17']++;
        }
    } elseif ($umur >= 18 && $umur <= 25) {
        if ($item['jenis_kelamin'] === 'MALE') {
            $maleCount['18-25']++;
        } elseif ($item['jenis_kelamin'] === 'FEMALE') {
            $femaleCount['18-25']++;
        }
    } elseif ($umur >= 26 && $umur <= 35) {
        if ($item['jenis_kelamin'] === 'MALE') {
            $maleCount['26-35']++;
        } elseif ($item['jenis_kelamin'] === 'FEMALE') {
            $femaleCount['26-35']++;
        }
    } elseif ($umur >= 36 && $umur <= 45) {
        if ($item['jenis_kelamin'] === 'MALE') {
            $maleCount['36-45']++;
        } elseif ($item['jenis_kelamin'] === 'FEMALE') {
            $femaleCount['36-45']++;
        }
    } elseif ($umur >= 46 && $umur <= 55) {
        if ($item['jenis_kelamin'] === 'MALE') {
            $maleCount['46-55']++;
        } elseif ($item['jenis_kelamin'] === 'FEMALE') {
            $femaleCount['46-55']++;
        }
    } elseif ($umur >= 56) {
        if ($item['jenis_kelamin'] === 'MALE') {
            $maleCount['56+']++;
        } elseif ($item['jenis_kelamin'] === 'FEMALE') {
            $femaleCount['56+']++;
        }
    }
}
foreach ($maleCount as $age => $count) {
    $sheet2->setCellValue('B' . ($row), $count); // Mengisi kolom Laki-laki
    $sheet2->setCellValue('C' . ($row), $femaleCount[$age]); // Mengisi kolom Perempuan
    $sheet2->setCellValue('D' . ($row), $count + $femaleCount[$age]); // Mengisi kolom Total
    $row++;
}
// Menghitung total Laki-laki dan Perempuan di baris Grand Total
$sheet2->setCellValue('B8', array_sum($maleCount));
$sheet2->setCellValue('C8', array_sum($femaleCount));
$sheet2->setCellValue('D8', array_sum($maleCount) + array_sum($femaleCount));

// Format sheet kedua
$sheet2->getStyle('A1:D8')->applyFromArray([
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN
        ]
    ]
]);

// Mengatur posisi data di tengah-tengah
$sheet2->getStyle('A1:D8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet2->getStyle('A1:D8')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

$sheet2->getStyle('A1:D1')->applyFromArray([
    'font' => ['bold' => true],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['argb' => 'FFFF00']
    ]
]);
// Format untuk Grand Total
$sheet2->getStyle('A' . $row . ':D' . $row)->applyFromArray([
    'font' => ['bold' => true],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['argb' => 'FFCC00']
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN
        ]
    ]
]);

// Mengatur lebar otomatis untuk kolom data
$sheet2->getColumnDimension('A')->setAutoSize(true);
$sheet2->getColumnDimension('B')->setAutoSize(true);
$sheet2->getColumnDimension('C')->setAutoSize(true);
$sheet2->getColumnDimension('D')->setAutoSize(true);

// Inisiasi variabel untuk menghitung jumlah aktif, tidak aktif, dan tetap
$jumlahAktif = 0;
$jumlahTidakAktif = 0;
$jumlahTetap = 0;

// Misalkan data status ada di sheet1 kolom C, mulai dari baris ke-2
$highestRow = $sheet1->getHighestRow(); // Mendapatkan baris terakhir yang memiliki data

// Iterasi melalui data di sheet1 Excel untuk menghitung status
for ($row = 2; $row <= $highestRow; $row++) {
    $status = $sheet1->getCell('C' . $row)->getValue(); // Ambil data status dari kolom C

    if (strtolower($status) == 'aktif') {
        $jumlahAktif++;
    } elseif (strtolower($status) == 'tidak aktif') {
        $jumlahTidakAktif++;
    } elseif (strtolower($status) == 'tetap') {
        $jumlahTetap++;
    }
}

// Iterasi melalui $items yang diambil dari database untuk menghitung status
foreach ($items as $item) {
    if (strtolower($item['Status']) === 'aktif') {
        $jumlahAktif++;
    } elseif (strtolower($item['Status']) === 'tidak aktif') {
        $jumlahTidakAktif++;
    } elseif (strtolower($item['Status']) === 'tetap') {
        $jumlahTetap++;
    } 
}

// Sheet kedua - Menampilkan jumlah Aktif, Tidak Aktif, dan Tetap
$sheet2->setCellValue('A13', 'Aktif');
$sheet2->setCellValue('B13', 'Tidak Aktif');
$sheet2->setCellValue('C13', 'Tetap');
$sheet2->setCellValue('D13', 'Jumlah');

// Menampilkan data hasil hitungan pada baris 14
$sheet2->setCellValue('A14', $jumlahAktif); // Menampilkan jumlah aktif
$sheet2->setCellValue('B14', $jumlahTidakAktif); // Menampilkan jumlah tidak aktif
$sheet2->setCellValue('C14', $jumlahTetap); // Menampilkan jumlah tetap

// Hitung jumlah total dan tampilkan di kolom D
$jumlahTotal = $jumlahAktif + $jumlahTidakAktif + $jumlahTetap;
$sheet2->setCellValue('D14', $jumlahTotal); // Menampilkan jumlah total

// Format untuk header (baris 13)
$sheet2->getStyle('A13:D13')->applyFromArray([
    'font' => ['bold' => true],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['argb' => 'FFFF00']
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN
        ]
    ]
]);

// Format untuk nilai jumlah di baris 14
$sheet2->getStyle('A14:D14')->applyFromArray([
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN
        ]
    ]
]);





// Set nilai untuk header di sheet2
$sheet2->setCellValue('F1', 'No');
$sheet2->setCellValue('G1', 'BA');
$sheet2->setCellValue('H1', 'MEKANIK TRAINEE');
$sheet2->setCellValue('I1', 'ADMIN TRAINEE');
$sheet2->setCellValue('J1', 'SALESMAN TRAINEE');
$sheet2->setCellValue('K1', 'COUNTER SALES TRAINEE');
$sheet2->setCellValue('L1', 'PKL');
$sheet2->setCellValue('M1', 'MAGANG STAR H23');
$sheet2->setCellValue('N1', 'Jumlah');

// Mengatur format untuk header
$sheet2->getStyle('F1:n1')->applyFromArray([
    'font' => ['bold' => true],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['argb' => 'FFFF00']
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN
        ]
    ]
]);

// Mengatur lebar kolom untuk header
$sheet2->getColumnDimension('F')->setWidth(5);   // No
$sheet2->getColumnDimension('G')->setWidth(20);  // BA
$sheet2->getColumnDimension('H')->setWidth(20);  // MEKANIK TRAINEE
$sheet2->getColumnDimension('I')->setWidth(15);  // ADMIN TRAINEE
$sheet2->getColumnDimension('J')->setWidth(25);  // SALESMAN TRAINEE
$sheet2->getColumnDimension('K')->setWidth(25);  // COUNTER SALES TRAINEE
$sheet2->getColumnDimension('L')->setWidth(10);  // PKL
$sheet2->getColumnDimension('M')->setWidth(15);  // MAGANG STAR
$sheet2->getColumnDimension('N')->setWidth(10);  // Jumlah

// Inisialisasi array untuk menyimpan jumlah posisi per BA
$baPositions = [];

// Hitung jumlah posisi per BA
foreach ($items as $item) {
    $ba_cabang = $item['ba_cabang'];

    // Jika BA belum ada di array, inisialisasi
    if (!isset($baPositions[$ba_cabang])) {
        $baPositions[$ba_cabang] = [
            'MEKANIK TRAINEE' => 0,
            'ADMIN TRAINEE' => 0,
            'SALESMAN TRAINEE' => 0,
            'COUNTER SALES TRAINEE' => 0,
            'PKL' => 0,
            'MAGANG STAR H23' => 0,
        ];
    }

    // Tambahkan jumlah posisi sesuai jenis posisi
    if ($item['posisi'] === 'MEKANIK TRAINEE') {
        $baPositions[$ba_cabang]['MEKANIK TRAINEE']++;
    }
    if ($item['posisi'] === 'ADMIN TRAINEE') {
        $baPositions[$ba_cabang]['ADMIN TRAINEE']++;
    }
    if ($item['posisi'] === 'SALESMAN TRAINEE') {
        $baPositions[$ba_cabang]['SALESMAN TRAINEE']++;
    }
    if ($item['posisi'] === 'COUNTER SALES TRAINEE') {
        $baPositions[$ba_cabang]['COUNTER SALES TRAINEE']++;
    }
    if ($item['posisi'] === 'PKL') {
        $baPositions[$ba_cabang]['PKL']++;
    }
    if ($item['posisi'] === 'MAGANG STAR H23') {
        $baPositions[$ba_cabang]['MAGANG STAR H23']++;
    }
}

// Sekarang, kita tulis data ke sheet Excel
$row = 2; // Mulai dari baris kedua setelah header
$no = 1; // Inisialisasi nomor untuk kolom No
$positionCounts = [
    'MEKANIK TRAINEE' => 0,
    'ADMIN TRAINEE' => 0,
    'SALESMAN TRAINEE' => 0,
    'COUNTER SALES TRAINEE' => 0,
    'PKL' => 0,
    'MAGANG STAR H23' => 0,
];

foreach ($baPositions as $ba_cabang => $positions) {
    // Menulis nomor dan BA
    $sheet2->setCellValue('F' . $row, $no);
    $sheet2->setCellValue('G' . $row, $ba_cabang);

   // Menulis jumlah posisi untuk BA ini, nilai 0 akan ditulis jika kosong
   $sheet2->setCellValue('H' . $row, $positions['MEKANIK TRAINEE'] ?: 0);
   $sheet2->setCellValue('I' . $row, $positions['ADMIN TRAINEE'] ?: 0);
   $sheet2->setCellValue('J' . $row, $positions['SALESMAN TRAINEE'] ?: 0);  // Pastikan SALESMAN TRAINEE diisi 0 jika kosong
   $sheet2->setCellValue('K' . $row, $positions['COUNTER SALES TRAINEE'] ?: 0);
   $sheet2->setCellValue('L' . $row, $positions['PKL'] ?: 0);
   $sheet2->setCellValue('M' . $row, $positions['MAGANG STAR H23'] ?: 0);;

    // Menambahkan rumus untuk menghitung total dari kolom H hingga L
    $sheet2->setCellValue('N' . $row, '=H' . $row . '+I' . $row . '+J' . $row . '+K' . $row . '+L' . $row. '+M' . $row);

    // Mengatur posisi semua sel di Grand Total agar berada di tengah
$sheet2->getStyle('F' . $row . ':N' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet2->getStyle('F' . $row . ':N' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    // Tambahkan ke grand total
    $positionCounts['MEKANIK TRAINEE'] += $positions['MEKANIK TRAINEE'];
    $positionCounts['ADMIN TRAINEE'] += $positions['ADMIN TRAINEE'];
    $positionCounts['SALESMAN TRAINEE'] += $positions['SALESMAN TRAINEE'];
    $positionCounts['COUNTER SALES TRAINEE'] += $positions['COUNTER SALES TRAINEE'];
    $positionCounts['PKL'] += $positions['PKL'];
    $positionCounts['MAGANG STAR H23'] += $positions['MAGANG STAR H23'];

    $row++;
    $no++;
}

// Menambahkan grand total di baris terakhir
$sheet2->setCellValue('G' . $row, 'Grand Total');
$sheet2->setCellValue('H' . $row, $positionCounts['MEKANIK TRAINEE']);
$sheet2->setCellValue('I' . $row, $positionCounts['ADMIN TRAINEE']);
$sheet2->setCellValue('J' . $row, $positionCounts['SALESMAN TRAINEE']);
$sheet2->setCellValue('K' . $row, $positionCounts['COUNTER SALES TRAINEE']);
$sheet2->setCellValue('L' . $row, $positionCounts['PKL']);
$sheet2->setCellValue('M' . $row, $positionCounts['MAGANG STAR H23']);
$sheet2->setCellValue('N' . $row, '=H' . $row . '+I' . $row . '+J' . $row . '+K' . $row . '+L' . $row. '+M' . $row); // Tambahkan rumus untuk Grand Total


// Mengatur posisi semua sel di Grand Total agar berada di tengah
$sheet2->getStyle('F' . $row . ':N' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet2->getStyle('F' . $row . ':N' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

// Format untuk Grand Total
$sheet2->getStyle('F' . $row . ':N' . $row)->applyFromArray([
    'font' => ['bold' => true],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['argb' => 'FFCC00']
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN
        ]
    ]
]);

// Mengaplikasikan border pada seluruh tabel di sheet kedua
$sheet2->getStyle('F1:N' . $row)->applyFromArray([
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN
        ]
    ]
]);




// Generate Excel file
$writer = new Xlsx($spreadsheet);

// Output file to browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Data trainee.xlsx"');
header('Cache-Control: max-age=0');

// Flush output buffer to prevent corrupt files
if (ob_get_contents()) ob_end_clean();

$writer->save('php://output');
exit;
} else {
    echo "Laporan tidak ditemukan untuk periode tersebut.";
}
?>
