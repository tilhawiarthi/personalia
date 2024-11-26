<?php
require_once '../koneksi.php';

$id = $_GET["id"];

$query = "DELETE FROM karyawan_magang WHERE id = '$id'";
$result = $conn->query($query);

if ($result) {
    echo "Karyawan magang berhasil dihapus";
    header("Location: data_trainee.php"); // redirect to home page
    exit;
} else {
    echo "Gagal menghapus karyawan magang";
}

$conn->close();
?>