<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'pencari') {
    header("Location: login.html");
    exit();
}
include 'koneksi.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "ID tidak ditemukan.";
    exit();
}

$sql = "SELECT * FROM kost WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    echo "Data kost tidak ditemukan.";
    exit();
}

$harga = $row['harga_perbulan'];
$harga_3bulan = $harga * 3;
$harga_6bulan = $harga * 6;
$harga_1tahun = $harga * 12;

$all_fasilitas = [];
$kolom = ['fasilitas_kamar', 'fasilitas_kamar_mandi', 'fasilitas_umum'];
foreach ($kolom as $key) {
    if (!empty($row[$key])) {
        $all_fasilitas = array_merge($all_fasilitas, explode(',', $row[$key]));
    }
}

$icons = [
    'wifi' => 'fa-wifi',
    'ac' => 'fa-snowflake',
    'kasur' => 'fa-bed',
    'lemari' => 'fa-box',
    'meja' => 'fa-table',
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
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Kost</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f6fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            background-color: #fff;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .kost-image {
            width: 100%;
            height: 360px;
            border-radius: 10px;
            object-fit: cover;
        }

        .kost-info {
            margin-top: 25px;
        }

        .kost-info h2 {
            margin-bottom: 10px;
            font-size: 28px;
            color: #2c3e50;
        }

        .kost-info p {
            margin: 6px 0;
            font-size: 16px;
            color: #444;
        }

        .harga {
            font-size: 20px;
            color: #195afe;
            margin-top: 15px;
        }

        .estimasi-harga {
            margin-top: 20px;
            background-color: #eef3ff;
            padding: 15px;
            border-radius: 8px;
        }

        .estimasi-harga h4 {
            margin-top: 0;
            color: #333;
        }

        .estimasi-harga ul {
            list-style: none;
            padding-left: 0;
        }

        .estimasi-harga li {
            padding: 4px 0;
        }

        .fasilitas {
            margin-top: 25px;
        }

        .fasilitas h4 {
            color: #333;
        }

        .fasilitas-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .fasilitas-list span {
            display: flex;
            align-items: center;
            gap: 6px;
            background-color: #f1f1f1;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 14px;
            color: #444;
        }

        .booking-form {
            margin-top: 30px;
            padding: 20px;
            background-color: #f8f9ff;
            border: 1px solid #ddd;
            border-radius: 10px;
        }

        .booking-form label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }

        .booking-form input[type="text"] {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin-bottom: 15px;
        }

        .booking-form button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .booking-form button:hover {
            background-color: #218838;
        }

        .back-btn {
            margin-top: 30px;
            display: inline-block;
            text-decoration: none;
            background-color: #195afe;
            color: #fff;
            padding: 10px 16px;
            border-radius: 6px;
        }

        .back-btn:hover {
            background-color: #003cc6;
        }
    </style>
</head>
<body>

<div class="container">
    <img class="kost-image" src="uploads/<?= htmlspecialchars($row['foto']) ?>" alt="Foto Kost">
    <div class="kost-info">
        <h2><?= htmlspecialchars($row['nama_kost']) ?></h2>
        <p><strong>Lokasi:</strong> <?= htmlspecialchars($row['lokasi']) ?></p>
        <p class="harga">Rp <?= number_format($row['harga_perbulan'], 0, ',', '.') ?>/bulan</p>

        <div class="estimasi-harga">
            <h4>Estimasi Harga:</h4>
            <ul>
                <li>3 Bulan: <strong>Rp <?= number_format($harga_3bulan, 0, ',', '.') ?></strong></li>
                <li>6 Bulan: <strong>Rp <?= number_format($harga_6bulan, 0, ',', '.') ?></strong></li>
                <li>1 Tahun: <strong>Rp <?= number_format($harga_1tahun, 0, ',', '.') ?></strong></li>
            </ul>
        </div>

        <div class="fasilitas">
            <h4>Fasilitas:</h4>
            <div class="fasilitas-list">
                <?php foreach ($all_fasilitas as $fas): ?>
                    <?php
                    $fas = strtolower(trim($fas));
                    $icon = $icons[$fas] ?? 'fa-circle';
                    ?>
                    <span><i class="fas <?= $icon ?>"></i> <?= ucwords($fas) ?></span>
                <?php endforeach; ?>
            </div>
        </div>

     <!-- Form Booking -->
<div class="booking-form">
    <form action="proses_booking.php" method="POST">
        <input type="hidden" name="id_kost" value="<?= $row['id'] ?>">
        <input type="hidden" name="id_pemilik" value="<?= $row['user_id'] ?>">
        <div class="form-group mt-3">
            <label>Nomor Telepon Anda:</label>
            <input type="text" name="nomor_telepon" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success mt-2">Booking Sekarang</button>
    </form>
</div>
        <a href="dashboard_pencari.php" class="back-btn"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>
    </div>
</div>

</body>
</html>