<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "personalia";

// Membuat koneksi
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Periksa koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());  

} else {
    $db = $conn; // Asumsikan $db digunakan sebagai alias untuk $conn
}
?>

