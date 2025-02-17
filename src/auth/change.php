<?php
session_start();
include '../service/utility.php';

if (isset($_SESSION['email'])) {
    return redirect("../admin.php");
}

if (!isset($_GET['reset'])) {
    return redirect("auth/login.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-red-950 flex items-center justify-center min-h-screen">

    <!-- Container Utama -->
    <div class="relative w-full max-w-md p-6 bg-gray-800 text-white rounded-lg shadow-lg">
        
        <!-- Tombol Kembali di Pojok Kiri -->
        <a href="forgot.php" class="absolute top-4 left-4 text-gray-300 hover:text-white text-lg">
            <i class="bi bi-arrow-left"></i>
        </a>

        <!-- Form Change Password -->
        <h2 class="text-2xl font-bold text-center text-white mb-6">Change Password</h2>

        <form action="../service/auth.php" method="POST">
            <input type="hidden" name="reset" value="<?= $_GET['reset'] ?>">

            <!-- Password Baru -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-300">New Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input type="password" id="password" name="new_password" class="pl-10 w-full p-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:ring focus:ring-red-400 focus:outline-none" placeholder="Enter new password" required>
                </div>
            </div>

            <!-- Konfirmasi Password Baru -->
            <div class="mb-4">
                <label for="confirm_password" class="block text-sm font-medium text-gray-300">Confirm Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input type="password" id="confirm_password" name="confirm_new_password" class="pl-10 w-full p-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:ring focus:ring-red-400 focus:outline-none" placeholder="Confirm new password" required>
                </div>
            </div>

            <!-- Tombol Change Password -->
            <button type="submit" name="type" value="edit_password" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold p-2 rounded-lg mt-4">
                Change Password
            </button>
        </form>
    </div>

</body>

</html>
