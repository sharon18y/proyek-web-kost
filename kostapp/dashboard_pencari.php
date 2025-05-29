<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'pencari') {
    header("Location: login.html");
    exit();
}

include 'koneksi.php';

$order = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'DESC' : 'ASC';

$sql = "SELECT * FROM kost ORDER BY harga_perbulan $order";
$result = $conn->query($sql);
?><!DOCTYPE html><html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Pencari</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    * {
      box-sizing: border-box;
    }body {
  font-family: 'Segoe UI', sans-serif;
  margin: 0;
  background-color: #eef1f5;
}

.sidebar {
  position: fixed;
  left: 0;
  top: 0;
  width: 240px;
  height: 100vh;
  background-color: #195afe;
  color: #fff;
  padding-top: 70px;
  transition: transform 0.3s ease;
  z-index: 1000;
}

.sidebar.hidden {
  transform: translateX(-100%);
}

.sidebar h2 {
  text-align: center;
  margin-bottom: 30px;
}

.sidebar a {
  display: flex;
  align-items: center;
  gap: 10px;
  color: white;
  padding: 15px 25px;
  text-decoration: none;
  transition: background 0.3s;
}

.sidebar a:hover {
  background-color: #003cc6;
}

.menu-toggle {
  position: fixed;
  top: 20px;
  left: 20px;
  font-size: 24px;
  cursor: pointer;
  z-index: 1100;
  background-color: #195afe;
  color: #fff;
  padding: 8px 10px;
  border-radius: 6px;
}

.main-content {
  margin-left: 240px;
  padding: 30px;
  transition: margin-left 0.3s ease;
}

.sidebar.hidden ~ .main-content {
  margin-left: 0;
}

.header-title {
  text-align: center;
  font-size: 26px;
  margin-bottom: 20px;
  color: #2c3e50;
}

.filter {
  margin-bottom: 30px;
  background: #fff;
  padding: 15px;
  border-radius: 10px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  display: flex;
  gap: 10px;
  align-items: center;
  flex-wrap: wrap;
}

.filter select {
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 6px;
}

.filter button {
  padding: 8px 14px;
  background-color: #195afe;
  border: none;
  color: #fff;
  border-radius: 6px;
  cursor: pointer;
}

.kost-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 20px;
}

.kost-card {
  background: #fff;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  transition: transform 0.2s ease;
  text-decoration: none;
  color: inherit;
}

.kost-card:hover {
  transform: translateY(-4px);
}

.kost-card img {
  width: 100%;
  height: 180px;
  object-fit: cover;
}

.kost-card .content {
  padding: 15px;
}

.kost-card h3 {
  margin: 0 0 10px;
  font-size: 18px;
}

.kost-card p {
  margin: 0;
  color: #555;
  font-size: 14px;
}

.fasilitas {
  margin-top: 10px;
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  font-size: 14px;
  color: #444;
}

.fasilitas span {
  display: flex;
  align-items: center;
  gap: 6px;
  background: #eef3ff;
  padding: 5px 10px;
  border-radius: 6px;
}

  </style>
</head>
<body><div class="menu-toggle" onclick="toggleSidebar()">
  <i class="fas fa-bars"></i>
</div><div class="sidebar" id="sidebar">
  <h2 style="margin-top:0;">Menu</h2>
  <a href="riwayat_booking.php"><i class="fas fa-clock-rotate-left"></i> Riwayat Booking</a>
  <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div><div class="main-content" id="main">
  <h1 class="header-title">Pencari Kost</h1>  <form class="filter" method="GET">
    <label for="order">Urutkan harga:</label>
    <select name="order" id="order">
      <option value="asc" <?= $order === 'ASC' ? 'selected' : '' ?>>Termurah ke Termahal</option>
      <option value="desc" <?= $order === 'DESC' ? 'selected' : '' ?>>Termahal ke Termurah</option>
    </select>
    <button type="submit">Terapkan</button>
  </form>  <div class="kost-container">
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <?php
          $all_fasilitas = [];
          $kolom = ['fasilitas_kamar', 'fasilitas_kamar_mandi', 'fasilitas_umum'];
          foreach ($kolom as $key) {
              if (!empty($row[$key])) {
                  $all_fasilitas = array_merge($all_fasilitas, explode(',', $row[$key]));
              }
          }$icons = [
          'wifi' => 'fa-wifi',
          'ac' => 'fa-snowflake',
          'kasur' => 'fa-bed',
          'lemari' => 'fa-box',
          'meja' => 'fa-chair',
          'kursi' => 'fa-chair',
          'kamar mandi dalam' => 'fa-toilet',
          'kipas' => 'fa-fan',
          'bak mandi' => 'fa-bath',
          'jongkok' => 'fa-toilet-portable',
          'kloset jongkok' => 'fa-toilet-portable',
          'jemuran' => 'fa-shirt',
          'parkiran' => 'fa-car',
          'dapur' => 'fa-fire-burner'
      ];
    ?>
    <a href="detail_kost.php?id=<?= $row['id'] ?>" class="kost-card">
      <img src="uploads/<?= htmlspecialchars($row['foto']) ?>" alt="Foto Kost">
      <div class="content">
        <h3><?= htmlspecialchars($row['nama_kost']) ?></h3>
        <p>Lokasi: <?= htmlspecialchars($row['lokasi']) ?></p>
        <p>Harga: Rp <?= number_format($row['harga_perbulan'], 0, ',', '.') ?>/bulan</p>
        <div class="fasilitas">
          <?php foreach ($all_fasilitas as $fas): ?>
            <?php
              $fas = strtolower(trim($fas));
              $iconClass = $icons[$fas] ?? 'fa-circle';
            ?>
            <span><i class="fas <?= $iconClass ?>"></i> <?= ucwords($fas) ?></span>
          <?php endforeach; ?>
        </div>
      </div>
    </a>
  <?php endwhile; ?>
<?php else: ?>
  <p>Tidak ada kost yang ditemukan.</p>
<?php endif; ?>

  </div>
</div><script>
  function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    const main = document.getElementById("main");

    sidebar.classList.toggle("hidden");
    main.style.marginLeft = sidebar.classList.contains("hidden") ? "0" : "240px";
  }

  document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".kost-card").forEach(card => {
      card.addEventListener("mouseenter", () => {
        card.style.boxShadow = "0 4px 12px rgba(25,90,254,0.3)";
      });
      card.addEventListener("mouseleave", () => {
        card.style.boxShadow = "0 2px 8px rgba(0,0,0,0.1)";
      });
    });
  });
</script></body>
</html>