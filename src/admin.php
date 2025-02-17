<?php 
session_start();
require_once 'service/connection.php'; 

if(!isset($_SESSION['username'])){
    header('location:auth/login.php');
    exit();
}

if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    $stmt = $conn->prepare("SELECT username FROM user WHERE id = ?");
    $stmt->bind_param("i", $id); 
    $stmt->execute();
    $stmt->bind_result($username);
    if ($stmt->fetch()) {
        $displayName = htmlspecialchars($username);
    } else {
        $displayName = "Guest";
    }
    $stmt->close();
} else {
    $displayName = "Guest";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undangan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-red-950 flex flex-col items-center justify-center min-h-screen text-white">

    <!-- Tombol Kembali -->
    <div class="absolute top-5 left-5">
        <a href="auth/login.php" class="text-white text-2xl hover:text-gray-300">
            <i class="bi bi-arrow-left"></i>
        </a>
    </div>

    <!-- Container Utama -->
    <div class="text-center">
        <h1 class="text-4xl font-bold mb-6">Undangan</h1>
        
        <!-- Logo -->
        <div class="mb-6">
            <img src="assets/img/Logo 71 (1).png" alt="Logo SMK Negeri 71" class="w-40 h-38 rounded-full mx-auto">
        </div>

        <!-- Teks Selamat Datang -->
        <p class="text-xl">Selamat Datang</p>
        <h3 class="text-2xl font-semibold mt-2"><?php echo $displayName; ?></h3>
        <p class="text-lg mt-4">Silahkan Pilih Aktivitas di bawah!</p>

        <!-- Tombol Aktivitas -->
        <div class="flex justify-center gap-6 mt-6">
            <button onclick="window.location.href='activities/tambahundangan.php'" class="bg-white text-red-900 w-14 h-14 rounded-lg flex justify-center items-center hover:bg-gray-300 transition">
                <i class="bi bi-plus text-2xl">+</i>
            </button>
            <button onclick="window.location.href='activities/kirimundangan.php'" class="bg-white text-red-900 w-14 h-14 rounded-lg flex justify-center items-center hover:bg-gray-300 transition">
                <i class="bi bi-send text-2xl">--></i>
            </button>
            <button onclick="window.location.href='activities/lihatundangan.php'" class="bg-white text-red-900 w-14 h-14 rounded-lg flex justify-center items-center hover:bg-gray-300 transition">
                <i class="bi bi-eye text-2xl">0</i>
            </button>
        </div>

        <!-- Tombol Logout -->
        <button onclick="window.location.href='auth/logout.php'" class="mt-8 px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-lg text-lg font-semibold transition">
            Logout
        </button>
    </div>

    <!-- SweetAlert untuk notifikasi -->
    <?php
    if (isset($_SESSION['success'])) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '" . $_SESSION['success'] . "',
                showConfirmButton: true
            });
        </script>";
        unset($_SESSION['success']);
    }
    ?>

</body>
</html>
