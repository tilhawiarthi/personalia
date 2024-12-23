<?php
require_once '../koneksi.php';

$id = $_GET["id"];

$query = "DELETE FROM os WHERE id = '$id'";
$result = $conn->query($query);

if ($result) {
    echo "Data OS Berhasil Dihapus";
    header("Location: download-dataos.php"); // redirect to home page
    exit;
} else {
    echo "Gagal menghapus karyawan magang";
}

$conn->close();
?>