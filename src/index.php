<?php
session_start();

if (!isset($_SESSION['username'])) {
    // Jika pengguna belum login, tampilkan halaman login
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Undangan-Web</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-red-950 flex items-center justify-center h-screen text-white">

    
        <div class="text-center">
        <div class="mb-6">
            <img src="assets/img/Logo 71 (1).png" alt="Logo SMK Negeri 71" class="w-40 h-38 rounded-full mx-auto">
        </div>
            <h1 class="text-6xl font-bold">Website-Undangan</h1>
            <p class="text-lg text-gray-300 mt-4">
                Buat Undangan Sesuai
                <span class="text-blue-400">Keinginan</span>, 
                <span class="text-blue-400">Keperluan</span>, 
                <!-- <span class="text-blue-400"></span>  -->
                dan <span class="text-blue-400">Kebutuhan Anda</span>.
            </p>
            <a href="auth/login.php">
                <button class="mt-6 px-6 py-3 bg-blue-600 hover:bg-blue-500 rounded-lg text-white text-lg font-medium">
                    Mulai Sekarang
                </button>
            </a>
        </div>

    </body>
    </html>
    <?php
    exit();
}

// Jika sudah login, arahkan ke admin.php
header('Location: admin.php');
exit();
?>
