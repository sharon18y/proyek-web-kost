<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'pencari') {
    header("Location: login.html");
    exit();
}

$id_pencari = $_SESSION['user_id'];

$query = "
    SELECT b.tanggal_booking, k.nama_kost, k.lokasi, k.harga_perbulan, b.nomor_telepon 
    FROM booking b
    JOIN kost k ON b.id_kost = k.id
    WHERE b.id_pencari = ?
    ORDER BY b.tanggal_booking DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_pencari);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Booking</title>
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

        h2 {
            margin-bottom: 20px;
            color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background-color: #195afe;
            color: #fff;
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
    <h2>Riwayat Booking Anda</h2>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama Kost</th>
                    <th>Lokasi</th>
                    <th>Harga/Bulan</th>
                    <th>No. Telepon</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['tanggal_booking']) ?></td>
                        <td><?= htmlspecialchars($row['nama_kost']) ?></td>
                        <td><?= htmlspecialchars($row['lokasi']) ?></td>
                        <td>Rp <?= number_format($row['harga_perbulan'], 0, ',', '.') ?></td>
                        <td><?= htmlspecialchars($row['nomor_telepon']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Belum ada booking yang Anda lakukan.</p>
    <?php endif; ?>

    <a href="dashboard_pencari.php" class="back-btn"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>
</div>

</body>
</html>