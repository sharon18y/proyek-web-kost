[18.33, 12/5/2025] L: <?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'pemilik') {
    echo "Akses ditolak.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_booking'])) {
    $id_booking = $_POST['id_booking'];
    $id_pemilik = $_SESSION['user_id'];

    $stmt = $conn->prepare("DELETE FROM booking WHERE id = ? AND id_pemilik = ?");
    $stmt->bind_param("ii", $id_booking, $id_pemilik);

    if ($stmt->execute()) {
        header("Location: dashboard_booking_pemilik.php");
        exit;
    } else {
        echo "Gagal menghapus booking.";
    }
} else {
    echo "Permintaan tidak valid.";
}
?>
[18.34, 12/5/2025] L: <?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'pemilik') {
    echo "Akses ditolak.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_booking'])) {
    $id_booking = $_POST['id_booking'];
    $id_pemilik = $_SESSION['user_id'];

    $stmt = $conn->prepare("DELETE FROM booking WHERE id = ? AND id_pemilik = ?");
    $stmt->bind_param("ii", $id_booking, $id_pemilik);

    if ($stmt->execute()) {
        header("Location: dashboard_booking_pemilik.php");
        exit;
    } else {
        echo "Gagal menghapus booking.";
    }
} else {
    echo "Permintaan tidak valid.";
}
?>