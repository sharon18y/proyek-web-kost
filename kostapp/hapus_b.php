<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

$user_id = $_SESSION['user_id'];

// Ambil data booking yang masuk untuk kost milik pemilik ini
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
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Booking Masuk</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f4f6f9;
      padding: 30px;
    }

    h1 {
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
<body>

<a href="dashboard_pemilik.php" class="back-btn"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>

<h1>Daftar Booking Kost Anda</h1>

<?php if ($result->num_rows > 0): ?>
  <?php while ($row = $result->fetch_assoc()): ?>
    <div class="booking-card">
      <h3><?php echo htmlspecialchars($row['nama_kost']); ?></h3>
      <p><strong>Email Pencari:</strong> <?php echo htmlspecialchars($row['email_pencari']); ?></p>
      <p><strong>Nomor Telepon:</strong> <?php echo htmlspecialchars($row['nomor_telepon']); ?></p>
      <p><strong>Tanggal Booking:</strong> <?php echo htmlspecialchars($row['tanggal_booking']); ?></p>
    </div>
  <?php endwhile; ?>
<?php else: ?>
  <p>Belum ada booking masuk untuk kost Anda.</p>
<?php endif; ?>

</body>
</html>