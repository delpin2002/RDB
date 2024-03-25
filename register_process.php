<?php
session_start();
include("engine/connection.php");
$dbh = $connection;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $namaLengkap = $_POST['inputFirstName'];
    $npp = $_POST['inputLastName'];
    $email = $_POST['inputEmail'];
    $password = $_POST['inputPassword'];
    $confirmPassword = $_POST['inputPasswordConfirm'];

    if ($password !== $confirmPassword) {
        $_SESSION['error'] = "Password dan konfirmasi password tidak cocok!";
        header("Location: register.php");
        exit;
    }

    $checkQuery = $dbh->prepare("SELECT tks_npp FROM rdb_user1 WHERE tks_npp = ?");
    $checkQuery->bind_param("s", $npp);
    $checkQuery->execute();
    $result = $checkQuery->get_result();
    if ($result->num_rows > 0) {
        $_SESSION['error'] = "NPP sudah terdaftar. Gunakan NPP lain!";
        header("Location: register.php");
        exit;
    }

    $psalt = bin2hex(random_bytes(16));
    $siteSalt = "jocoogja";
    $hashedPassword = hash('sha256', $password . $siteSalt . $psalt);

    $sql = $dbh->prepare("INSERT INTO rdb_user1 (tks_nama_lengkap, tks_npp, tks_email, tks_pass, psalt) VALUES (?, ?, ?, ?, ?)");
    $sql->bind_param("sssss", $namaLengkap, $npp, $email, $hashedPassword, $psalt);

    if ($sql->execute()) {
        $_SESSION['success'] = "Pendaftaran berhasil! Silakan login.";
    } else {
        $_SESSION['error'] = "Terjadi kesalahan saat menyimpan data: " . $dbh->error;
    }
    header("Location: register.php");
    exit;
}
?>
