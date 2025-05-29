[18.52, 12/5/2025] L: <?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

$user_id = $_SESSION['user_id'];
$notifQuery = $conn->prepare("SELECT COUNT(*) AS jumlah FROM booking WHERE id_pemilik = ?");
$notifQuery->bind_param("i", $user_id);
$notifQuery->execute();
$notifResult = $notifQuery->get_result();
$notifRow = $notifResult->fetch_assoc();
$jumlah_booking = $notifRow['jumlah'];

$query = $conn->prepare("SELECT * FROM kost WHERE user_id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Pemilik K…
[18.56, 12/5/2025] L: <?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

$user_id = $_SESSION['user_id'];

// Hitung jumlah booking
$notifQuery = $conn->prepare("SELECT COUNT(*) AS jumlah FROM booking WHERE id_pemilik = ?");
$notifQuery->bind_param("i", $user_id);
$notifQuery->execute();
$notifResult = $notifQuery->get_result();
$notifRow = $notifResult->fetch_assoc();
$jumlah_booking = $notifRow['jumlah'];

// Ambil daftar kost
$query = $conn->prepare("SELECT * FROM kost WHERE user_id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Pemilik Kost</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background-color: #f4f6f9;
    }

    .menu-toggle {
      position: fixed;
      top: 15px;
      left: 15px;
      font-size: 26px;
      color: #195afe;
      background-color: white;
      border-radius: 5px;
      padding: 6px 10px;
      cursor: pointer;
      z-index: 1001;
      box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: 250px;
      height: 100%;
      background-color: #195afe;
      padding-top: 60px;
      transition: transform 0.3s ease;
      transform: translateX(0);
      z-index: 1000;
    }

    .sidebar.hide {
      transform: translateX(-100%);
    }

    .sidebar a {
      color: white;
      display: block;
      padding: 15px 20px;
      text-decoration: none;
      transition: background 0.3s;
    }

    .sidebar a:hover {
      background-color: #003cc6;
    }

    .main {
      margin-left: 250px;
      padding: 70px 20px 30px 20px;
      transition: margin-left 0.3s ease;
    }

    .main.full {
      margin-left: 0;
    }

    .dashboard-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 30px;
    }

    .btn-primary {
      background-color: #195afe;
      border: none;
      color: white;
      padding: 10px 16px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 14px;
    }

    .kost-card {
      display: flex;
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
      margin-bottom: 20px;
      overflow: hidden;
    }

    .kost-card img {
      width: 200px;
      height: 150px;
      object-fit: cover;
    }

    .kost-content {
      padding: 15px;
      flex: 1;
    }

    .kost-content h3 {
      margin: 0;
      font-size: 18px;
      color: #195afe;
    }

    .kost-content p {
      margin: 4px 0;
      font-size: 14px;
      color: #333;
    }

    .kost-price {
      font-weight: bold;
      color: #000;
      font-size: 16px;
      margin-top: 10px;
    }

    .notif-badge {
      background-color: red;
      color: white;
      font-size: 12px;
      border-radius: 50%;
      padding: 2px 6px;
      margin-left: 5px;
    }

    @media (max-width: 768px) {
      .main {
        margin-left: 0;
      }

      .sidebar {
        transform: translateX(-100%);
      }

      .sidebar.show {
        transform: translateX(0);
      }
    }
  </style>
</head>
<body>

<div class="menu-toggle" onclick="toggleSidebar()">
  <i class="fas fa-bars"></i>
</div>

<div class="sidebar" id="sidebar">
  <a href="tambah_kost.php"><i class="fas fa-plus-circle"></i> Tambah Kost</a>
  <a href="daftar_kost.php"><i class="fas fa-list"></i> Daftar Kost</a>
  <a href="dashboard_booking_pemilik.php">
    <i class="fas fa-calendar-check"></i> Lihat Booking
    <?php if ($jumlah_booking > 0): ?>
      <span class="notif-badge"><?php echo $jumlah_booking; ?></span>
    <?php endif; ?>
  </a>
  <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="main" id="main">
  <div class="dashboard-header">
    <h1>Selamat datang, Pemilik Kost!</h1>
    <a href="tambah_kost.php"><button class="btn-primary">+ Tambah Kost</button></a>
  </div>

  <?php while ($row = $result->fetch_assoc()): ?>
    <div class="kost-card">
      <?php if (!empty($row['foto'])): ?>
        <img src="uploads/<?php echo htmlspecialchars($row['foto']); ?>" alt="Foto Kost">
      <?php else: ?>
        <img src="default.jpg" alt="Default Kost">
      <?php endif; ?>
      <div class="kost-content">
        <h3><?php echo htmlspecialchars($row['nama_kost']); ?></h3>
        <p><strong><?php echo htmlspecialchars($row['lokasi']); ?></strong></p>
        <p><?php echo htmlspecialchars($row['fasilitas_kamar']); ?> · <?php echo htmlspecialchars($row['fasilitas_kamar_mandi']); ?> · <?php echo htmlspecialchars($row['fasilitas_umum']); ?></p>
        <div class="kost-price">Rp<?php echo number_format((int)$row['harga_perbulan'], 0, ',', '.'); ?>/bulan</div>
      </div>
    </div>
  <?php endwhile; ?>

</div>

<script>
  function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const main = document.getElementById('main');
    sidebar.classList.toggle('hide');
    main.classList.toggle('full');
  }
</script>

</body>
</html>