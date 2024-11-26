<?php
session_start();

// Menghapus semua variabel sesi
session_unset();

// Menghancurkan sesi
session_destroy();

// Mengarahkan ke halaman index.php
header("Location: index.php");
exit();
?>
