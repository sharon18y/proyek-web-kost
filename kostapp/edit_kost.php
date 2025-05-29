<?php
session_start();
include 'koneksi.php';

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_kost = $_POST['nama_kost'];
    $lokasi = $_POST['lokasi'];
    $harga = str_replace('.', '', $_POST['harga_bulan']);
    $fasilitas_kamar = isset($_POST['fasilitas_kamar']) ? implode(', ', $_POST['fasilitas_kamar']) : '';
    $fasilitas_mandi = isset($_POST['fasilitas_mandi']) ? implode(', ', $_POST['fasilitas_mandi']) : '';
    $fasilitas_umum = isset($_POST['fasilitas_umum']) ? implode(', ', $_POST['fasilitas_umum']) : '';

    // Upload foto baru jika ada
    if ($_FILES['foto']['error'] === 0) {
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        $foto = uniqid() . "_" . basename($_FILES["foto"]["name"]);
        move_uploaded_file($_FILES["foto"]["tmp_name"], $target_dir . $foto);

        $stmt = $conn->prepare("UPDATE kost SET nama_kost=?, lokasi=?, harga_perbulan=?, fasilitas_kamar=?, fasilitas_kamar_mandi=?, fasilitas_umum=?, foto=? WHERE id=? AND user_id=?");
        $stmt->bind_param("ssissssii", $nama_kost, $lokasi, $harga, $fasilitas_kamar, $fasilitas_mandi, $fasilitas_umum, $foto, $id, $user_id);
    } else {
        $stmt = $conn->prepare("UPDATE kost SET nama_kost=?, lokasi=?, harga_perbulan=?, fasilitas_kamar=?, fasilitas_kamar_mandi=?, fasilitas_umum=? WHERE id=? AND user_id=?");
        $stmt->bind_param("ssisssii", $nama_kost, $lokasi, $harga, $fasilitas_kamar, $fasilitas_mandi, $fasilitas_umum, $id, $user_id);
    }

    if ($stmt->execute()) {
        header("Location: daftar_kost.php?success=edit");
        exit;
    } else {
        echo "Gagal update: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    exit;
}

// Ambil data kost
$stmt = $conn->prepare("SELECT * FROM kost WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();
$conn->close();

// Konversi fasilitas ke array
$f_kamar = explode(', ', $data['fasilitas_kamar']);
$f_mandi = explode(', ', $data['fasilitas_kamar_mandi']);
$f_umum = explode(', ', $data['fasilitas_umum']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Kost</title>
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
  <h2>Edit Kost</h2>
  <form action="" method="POST" enctype="multipart/form-data">
    <label for="nama_kost">Nama Kost</label>
    <input type="text" name="nama_kost" value="<?= htmlspecialchars($data['nama_kost']) ?>" required>

    <label for="lokasi">Lokasi</label>
    <input type="text" name="lokasi" value="<?= htmlspecialchars($data['lokasi']) ?>" required>

    <label for="harga_bulan">Harga per Bulan</label>
    <input type="text" id="harga_bulan" name="harga_bulan" value="<?= number_format($data['harga_perbulan'], 0, '.', '.') ?>" required>

    <label>Fasilitas Kamar</label>
    <div class="checkbox-group">
      <?php
        $opsi_kamar = ['Kasur', 'Meja', 'Lemari', 'Kursi', 'Kipas', 'AC', 'Dapur'];
        foreach ($opsi_kamar as $val) {
          $checked = in_array($val, $f_kamar) ? 'checked' : '';
          echo "<label><input type='checkbox' name='fasilitas_kamar[]' value='$val' $checked>$val</label>";
        }
      ?>
    </div>

    <label>Fasilitas Kamar Mandi</label>
    <div class="checkbox-group">
      <?php
        $opsi_mandi = ['Kloset Jongkok', 'Kamar Mandi Dalam', 'Bak Mandi'];
        foreach ($opsi_mandi as $val) {
          $checked = in_array($val, $f_mandi) ? 'checked' : '';
          echo "<label><input type='checkbox' name='fasilitas_mandi[]' value='$val' $checked>$val</label>";
        }
      ?>
    </div>

    <label>Fasilitas Umum</label>
    <div class="checkbox-group">
      <?php
        $opsi_umum = ['WiFi', 'Jemuran', 'Parkiran'];
        foreach ($opsi_umum as $val) {
          $checked = in_array($val, $f_umum) ? 'checked' : '';
          echo "<label><input type='checkbox' name='fasilitas_umum[]' value='$val' $checked>$val</label>";
        }
      ?>
    </div>

    <label>Foto Kost (Opsional)</label>
    <input type="file" name="foto">

    <button type="submit">Simpan Perubahan</button>
  </form>
</div>

<script>
  const hargaInput = document.getElementById('harga_bulan');
  function formatRupiah(angka) {
    return angka.replace(/\D/g, '')
                .replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  }

  hargaInput.addEventListener('input', function () {
    this.value = formatRupiah(this.value);
  });
</script>

</body>
</html>