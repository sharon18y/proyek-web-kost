<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM kost WHERE user_id = $user_id");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Kost</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f7f7f7;
      margin: 0;
      padding: 20px;
    }

    h2 {
      text-align: center;
      color: #195afe;
    }

    .kost-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 20px;
      margin-top: 30px;
    }

    .kost-card {
      background-color: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      overflow: hidden;
      position: relative;
    }

    .kost-card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }

    .kost-info {
      padding: 15px;
    }

    .kost-info h3 {
      margin: 0 0 8px;
      font-size: 18px;
      color: #333;
    }

    .kost-info p {
      margin: 5px 0;
      color: #555;
      font-size: 14px;
    }

    .harga {
      color: #195afe;
      font-weight: bold;
      font-size: 15px;
    }

    .aksi {
      display: flex;
      justify-content: space-between;
      margin-top: 10px;
    }

    .aksi a {
      text-decoration: none;
      padding: 6px 12px;
      font-size: 13px;
      border-radius: 5px;
      color: #fff;
    }

    .edit {
      background-color: #ffc107;
    }

    .hapus {
      background-color: #dc3545;
    }

    .hapus:hover {
      background-color: #c82333;
    }

    .edit:hover {
      background-color: #e0a800;
    }
  </style>
</head>
<body>

<h2>Daftar Kost Anda</h2>

<div class="kost-container">
  <?php while($row = $result->fetch_assoc()): ?>
    <div class="kost-card">
      <img src="uploads/<?= htmlspecialchars($row['foto']) ?>" alt="Foto Kost">
      <div class="kost-info">
        <h3><?= htmlspecialchars($row['nama_kost']) ?></h3>
        <p><?= htmlspecialchars($row['lokasi']) ?></p>
        <p class="harga">Rp <?= number_format($row['harga_perbulan'], 0, ',', '.') ?> / bulan</p>
        <p><strong>Fasilitas:</strong> <?= htmlspecialchars($row['fasilitas_kamar']) ?></p>

        <div class="aksi">
          <a href="edit_kost.php?id=<?= $row['id'] ?>" class="edit"><i class="fas fa-edit"></i> Edit</a>
          <a href="hapus_kost.php?id=<?= $row['id'] ?>" class="hapus" onclick="return confirm('Yakin ingin menghapus kost ini?')"><i class="fas fa-trash-alt"></i> Hapus</a>
        </div>
      </div>
    </div>
  <?php endwhile; ?>
</div>

</body>
</html>