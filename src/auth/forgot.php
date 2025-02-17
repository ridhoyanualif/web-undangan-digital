<?php
session_start();
include '../service/utility.php';

if (isset($_SESSION['email'])) {
    return redirect("admin.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-red-950 flex items-center justify-center min-h-screen">

    <!-- Container Utama -->
    <div class="relative w-full max-w-md p-6 bg-gray-800 text-white rounded-lg shadow-lg">
        
        <!-- Tombol Kembali di Pojok Kiri -->
        <a href="login.php" class="absolute top-4 left-4 text-gray-300 hover:text-white text-lg">
            <i class="bi bi-arrow-left"></i>
        </a>

        <!-- Form Reset Password -->
        <h2 class="text-2xl font-bold text-center text-white mb-6">Reset Password</h2>

        <form action="../service/auth.php" method="POST">
            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-300">Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input type="email" id="email" name="email" class="pl-10 w-full p-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:ring focus:ring-red-400 focus:outline-none" placeholder="Masukkan email" required>
                </div>
            </div>

            <!-- Username -->
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-300">Username</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="bi bi-person"></i>
                    </span>
                    <input type="text" id="username" name="username" class="pl-10 w-full p-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:ring focus:ring-red-400 focus:outline-none" placeholder="Masukkan username" required>
                </div>
            </div>

            <!-- Link ke Login -->
            

            <!-- Tombol Reset -->
            <button type="submit" name="type" value="find_email" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold p-2 rounded-lg mt-4">
                Reset Password
            </button>

            <div class="text-center mt-4">
                <a href="login.php" class="text-blue-600 hover:text-blue-500 text-sm">Sudah punya akun? Login</a>
            </div>
        </form>
    </div>

</body>

</html>
