<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'pencari') {
    echo "Anda harus login sebagai pencari kost.";
    exit;
}

$id_pencari = $_SESSION['user_id'];
$id_pemilik = $_POST['id_pemilik'] ?? null;
$id_kost = $_POST['id_kost'] ?? null;
$nomor_telepon = $_POST['nomor_telepon'] ?? '';
$tanggal = date("Y-m-d");

if (empty($id_pemilik) || empty($id_kost) || empty($nomor_telepon)) {
    echo "Data tidak lengkap.";
    exit;
}

$stmt = $conn->prepare("INSERT INTO booking (id_pencari, id_pemilik, id_kost, nomor_telepon, tanggal_booking) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("iiiss", $id_pencari, $id_pemilik, $id_kost, $nomor_telepon, $tanggal);

if ($stmt->execute()) {
    // Redirect ke halaman riwayat booking
    header("Location: riwayat_booking.php");
    exit();
} else {
    echo "Gagal melakukan booking: " . $stmt->error;
}
?>