<?php
session_start();
include '../service/utility.php';

if (isset($_SESSION['email'])) {
    return redirect("login.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-red-950 flex items-center justify-center min-h-screen">

    <!-- Container Utama -->
    <div class="relative w-full max-w-md p-6 bg-gray-800 text-white rounded-lg shadow-lg">
        <!-- Tombol Kembali di Pojok Kiri -->
        <a href="login.php" class="absolute top-4 left-4 text-gray-300 hover:text-white text-lg">
            <i class="bi bi-arrow-left"></i>
        </a>

        <!-- Form Registrasi -->
        <h2 class="text-2xl font-bold text-center text-white mb-6">Register</h2>

        <form action="../service/auth.php" method="POST">
            <!-- Nama Lengkap -->
            <div class="mb-4">
                <label for="nama" class="block text-sm font-medium text-gray-300">Nama Lengkap</label>
                <input type="text" id="nama" name="username" class="mt-1 w-full p-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:ring focus:ring-red-400 focus:outline-none" placeholder="Masukkan nama lengkap" required>
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-300">Email</label>
                <input type="email" id="email" name="email" class="mt-1 w-full p-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:ring focus:ring-red-400 focus:outline-none" placeholder="Masukkan email" required>
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-300">Password</label>
                <input type="password" id="password" name="password" class="mt-1 w-full p-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:ring focus:ring-red-400 focus:outline-none" placeholder="Masukkan password" required>
            </div>

            <!-- Konfirmasi Password -->
            <div class="mb-4">
                <label for="confirm_password" class="block text-sm font-medium text-gray-300">Confirm Password</label>
                <input type="password" id="confirm_password" name="c_password" class="mt-1 w-full p-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:ring focus:ring-red-400 focus:outline-none" placeholder="Konfirmasi password" required>
            </div>

            <!-- Tombol Register -->
            <button type="submit" name="type" value="register" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold p-2 rounded-lg mt-2">
                Sign Up
            </button>
        </form>

        <!-- Tombol Kembali ke Login -->
        <div class="text-center mt-4">
                <a href="login.php" class="text-blue-400 hover:underline">Back To Login</a>
            </div>
    </div>

</body>

</html>
