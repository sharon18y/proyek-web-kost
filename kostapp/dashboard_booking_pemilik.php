<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

$user_id = $_SESSION['user_id'];

// Update status booking jika ada aksi terima/tolak
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'], $_POST['status'])) {
    $booking_id = $_POST['booking_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE booking SET status = ? WHERE id = ? AND id_pemilik = ?");
    $stmt->bind_param("sii", $status, $booking_id, $user_id);
    $stmt->execute();
}

// Ambil data booking
$query = $conn->prepare("
    SELECT b.*, k.nama_kost, u.email AS email_pencari 
    FROM booking b 
    JOIN kost k ON b.id_kost = k.id 
    JOIN users u ON b.id_pencari = u.id 
    WHERE b.id_pemilik = ?
    ORDER BY b.tanggal_booking DESC
");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
?><!DOCTYPE html><html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Booking Masuk</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f4f6f9;
      padding: 30px;
    }h1 {
  color: #195afe;
}

.booking-card {
  background: white;
  border-radius: 10px;
  padding: 20px;
  margin-bottom: 20px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.05);
}

.booking-card h3 {
  margin: 0 0 10px 0;
  color: #195afe;
}

.booking-card p {
  margin: 5px 0;
  font-size: 14px;
  color: #333;
}

.booking-card form {
  margin-top: 10px;
  display: inline;
}

.booking-card button {
  padding: 8px 12px;
  margin-right: 5px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
}

.accept {
  background-color: #28a745;
  color: white;
}

.reject {
  background-color: #dc3545;
  color: white;
}

.delete {
  background-color: #6c757d;
  color: white;
}

.status {
  margin-top: 10px;
  font-weight: bold;
}

.back-btn {
  display: inline-block;
  margin-bottom: 20px;
  padding: 8px 16px;
  background-color: #195afe;
  color: white;
  text-decoration: none;
  border-radius: 6px;
}

  </style>
</head>
<body><a href="dashboard_pemilik.php" class="back-btn"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>

<h1>Daftar Booking Kost Anda</h1><?php if ($result->num_rows > 0): ?>  <?php while ($row = $result->fetch_assoc()): ?><div class="booking-card">
  <h3><?= htmlspecialchars($row['nama_kost']); ?></h3>
  <p><strong>Email Pencari:</strong> <?= htmlspecialchars($row['email_pencari']); ?></p>
  <p><strong>No. Telepon:</strong> <?= htmlspecialchars($row['nomor_telepon']); ?></p>
  <p><strong>Tanggal Booking:</strong> <?= htmlspecialchars($row['tanggal_booking']); ?></p>
  <p class="status">Status: <?= ucfirst($row['status']); ?></p>

  <?php if ($row['status'] === 'pending'): ?>
    <form method="POST">
      <input type="hidden" name="booking_id" value="<?= $row['id']; ?>">
      <button type="submit" name="status" value="diterima" class="accept">Terima</button>
      <button type="submit" name="status" value="ditolak" class="reject">Tolak</button>
    </form>
  <?php else: ?>
    <form method="POST" action="hapus_booking.php" onsubmit="return confirm('Yakin ingin menghapus booking ini?')">
      <input type="hidden" name="id_booking" value="<?= $row['id']; ?>">
      <button type="submit" class="delete">Hapus</button>
    </form>
  <?php endif; ?>
</div>

  <?php endwhile; ?><?php else: ?>  <p>Belum ada booking masuk untuk kost Anda.</p>
<?php endif; ?></body>
</html>