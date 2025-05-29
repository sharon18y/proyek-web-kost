<?php
include 'koneksi.php';

$email = strtolower(trim($_POST['email']));
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$role = $_POST['role'];

$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    header("Location: register.html?error=email_terdaftar");
    exit();
}
$stmt->close();

$stmt = $conn->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $email, $password, $role);

if ($stmt->execute()) {
    header("Location: login.html?success=1");
} else {
    echo "Gagal register: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
