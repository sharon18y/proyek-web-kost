<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $nama_kost = $_POST['nama_kost'];
    $lokasi = $_POST['lokasi'];
    $harga = str_replace('.', '', $_POST['harga_bulan']); // hilangkan titik dari harga

    $fasilitas_kamar = isset($_POST['fasilitas_kamar']) ? implode(', ', $_POST['fasilitas_kamar']) : '';
    $fasilitas_kamar_mandi = isset($_POST['fasilitas_mandi']) ? implode(', ', $_POST['fasilitas_mandi']) : '';
    $fasilitas_umum = isset($_POST['fasilitas_umum']) ? implode(', ', $_POST['fasilitas_umum']) : '';

    // Proses upload foto
    $foto = '';
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $foto = uniqid() . "_" . basename($_FILES["foto"]["name"]);
        $target_file = $target_dir . $foto;
        move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);
    }

    // Simpan ke database, termasuk lokasi
    $stmt = $conn->prepare("INSERT INTO kost (user_id, nama_kost, lokasi, harga_perbulan, fasilitas_kamar, fasilitas_kamar_mandi, fasilitas_umum, foto) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ississss", $user_id, $nama_kost, $lokasi, $harga, $fasilitas_kamar, $fasilitas_kamar_mandi, $fasilitas_umum, $foto);

    if ($stmt->execute()) {
        header("Location: dashboard_pemilik.php?success=tambah");
        exit();
    } else {
        echo "Gagal menambahkan kost: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Kost</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f6f9;
      margin: 0;
      padding: 30px;
    }

    .container {
      max-width: 700px;
      background: #fff;
      margin: auto;
      padding: 25px 30px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #195afe;
    }

    label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
    }

    input[type="text"],
    input[type="number"],
    textarea,
    input[type="file"] {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 14px;
    }

    .checkbox-group {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-top: 10px;
    }

    .checkbox-group label {
      font-weight: normal;
    }

    .checkbox-group input {
      margin-right: 5px;
    }

    .estimasi {
      margin-top: 10px;
      font-size: 14px;
      color: #333;
    }

    button {
      margin-top: 20px;
      width: 100%;
      background-color: #195afe;
      color: white;
      border: none;
      padding: 12px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 16px;
    }

    button:hover {
      background-color: #003cc6;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Tambah Kost</h2>
  <form action="tambah_kost.php" method="POST" enctype="multipart/form-data">
    <label for="nama_kost">Nama Kost</label>
    <input type="text" id="nama_kost" name="nama_kost" required>

    <label for="lokasi">Lokasi</label>
    <input type="text" id="lokasi" name="lokasi" required>

    <label for="harga_bulan">Harga per Bulan</label>
    <input type="text" id="harga_bulan" name="harga_bulan" required>

    <div class="estimasi">
      <p>Estimasi Harga:</p>
      <p>3 Bulan: <span id="harga_3">Rp 0</span></p>
      <p>6 Bulan: <span id="harga_6">Rp 0</span></p>
      <p>1 Tahun: <span id="harga_12">Rp 0</span></p>
    </div>

    <label>Fasilitas Kamar</label>
    <div class="checkbox-group">
      <label><input type="checkbox" name="fasilitas_kamar[]" value="Kasur">Kasur</label>
      <label><input type="checkbox" name="fasilitas_kamar[]" value="Meja">Meja</label>
      <label><input type="checkbox" name="fasilitas_kamar[]" value="Lemari">Lemari</label>
      <label><input type="checkbox" name="fasilitas_kamar[]" value="Kursi">Kursi</label>
      <label><input type="checkbox" name="fasilitas_kamar[]" value="Kipas">Kipas</label>
      <label><input type="checkbox" name="fasilitas_kamar[]" value="AC">AC</label>
      <label><input type="checkbox" name="fasilitas_kamar[]" value="Dapur">Dapur</label>
    </div>

    <label>Fasilitas Kamar Mandi</label>
    <div class="checkbox-group">
      <label><input type="checkbox" name="fasilitas_mandi[]" value="Kloset Jongkok">Kloset Jongkok</label>
      <label><input type="checkbox" name="fasilitas_mandi[]" value="Kamar Mandi Dalam">Kamar Mandi Dalam</label>
      <label><input type="checkbox" name="fasilitas_mandi[]" value="Bak Mandi">Bak Mandi</label>
    </div>

    <label>Fasilitas Umum</label>
    <div class="checkbox-group">
      <label><input type="checkbox" name="fasilitas_umum[]" value="WiFi">WiFi</label>
      <label><input type="checkbox" name="fasilitas_umum[]" value="Jemuran">Jemuran</label>
      <label><input type="checkbox" name="fasilitas_umum[]" value="Parkiran">Parkiran</label>
    </div>

    <label for="foto">Foto Kost</label>
    <input type="file" id="foto" name="foto" accept="image/*" required>

    <button type="submit">Simpan Kost</button>
  </form>
</div>

<script>
  const hargaInput = document.getElementById('harga_bulan');
  const harga3 = document.getElementById('harga_3');
  const harga6 = document.getElementById('harga_6');
  const harga12 = document.getElementById('harga_12');

  function formatRupiah(angka) {
    return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  }

  function cleanRupiah(rupiah) {
    return parseInt(rupiah.replace(/\./g, '')) || 0;
  }

  hargaInput.addEventListener('input', function(e) {
    let raw = hargaInput.value.replace(/\./g, '');
    if (isNaN(raw)) raw = '0';

    hargaInput.value = formatRupiah(raw);

    const bulanan = cleanRupiah(hargaInput.value);
    harga3.textContent = 'Rp ' + formatRupiah(bulanan * 3);
    harga6.textContent = 'Rp ' + formatRupiah(bulanan * 6);
    harga12.textContent = 'Rp ' + formatRupiah(bulanan * 12);
  });
</script>

</body>
</html> 