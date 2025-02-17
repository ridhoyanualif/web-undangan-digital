<?php
session_start();
include '../service/utility.php';

if (isset($_SESSION['email'])) {
    header("Location: admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undangan-Web</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-red-950 min-h-screen flex items-center justify-center text-white">

<div class="absolute top-5 left-5">
        <a href="../" class="flex items-center text-white text-lg hover:text-gray-300 transition">
            <i class="bi bi-arrow-left text-2xl"></i>
            <span class="ml-2">Kembali</span>
        </a>
    </div>

    <div class="relative w-full max-w-md p-6 bg-gray-800 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-center mb-4">Login</h2>

        <form action="../service/auth.php" method="POST">
            <!-- Input Email -->
            <div class="mb-4">
                <label for="email" class="block text-gray-300">Email</label>
                <div class="flex items-center bg-gray-700 p-2 rounded-md">
                    <i class="bi bi-envelope text-gray-400 mr-2"></i>
                    <input type="email" id="email" name="email" class="w-full bg-transparent outline-none text-white" placeholder="Enter email" required>
                </div>
            </div>

            <!-- Input Password -->
            <div class="mb-4">
                <label for="password" class="block text-gray-300">Password</label>
                <div class="flex items-center bg-gray-700 p-2 rounded-md">
                    <i class="bi bi-lock text-gray-400 mr-2"></i>
                    <input type="password" id="password" name="password" class="w-full bg-transparent outline-none text-white" placeholder="Enter password" required>
                </div>
            </div>

            <!-- Tombol Login -->
            <button type="submit" name="type" value="login" class="w-full bg-blue-600 hover:bg-blue-500 transition duration-200 text-white py-2 rounded-md text-lg font-medium">
                Sign in
            </button>

            <!-- Link tambahan -->
            <div class="text-center mt-4">
                <a href="forgot.php" class="text-blue-400 hover:underline">Forgot your password?</a>
                <p class="mt-2 text-gray-300">Don't have an account yet? <a href="register.php" class="text-blue-400 hover:underline">Register here</a></p>
            </div>
        </form>
    </div>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php
    if (isset($_SESSION['success']) && strlen($_SESSION['success']) > 3) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '" . htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8') . "',
                showConfirmButton: true
            });
        </script>";
        unset($_SESSION['success']);
    }

    if (isset($_SESSION['error']) && strlen($_SESSION['error']) > 3) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '" . htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') . "',
                showConfirmButton: true
            });
        </script>";
        unset($_SESSION['error']);
    }
    ?>
</body>

</html>
