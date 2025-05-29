<?php
session_start();
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Hapus data kost milik user
    $stmt = $conn->prepare("DELETE FROM kost WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $user_id);

    if ($stmt->execute()) {
        header("Location: daftar_kost.php?success=hapus");
    } else {
        echo "Gagal menghapus kost.";
    }

    $stmt->close();
    $conn->close();
}
?>