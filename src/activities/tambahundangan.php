<?php
session_start();
require '../service/connection.php'; // Pastikan koneksi database

if (!isset($_SESSION['username'])) {
    header('location:../auth/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pastikan semua variabel tidak kosong
    $judul_undangan = $_POST['judul_undangan'];
    $nama_event = $_POST['nama_event'];
    $desc_event = mysqli_real_escape_string($conn, $_POST['desc_event']);
    $start_event = $_POST['start_event'];
    $end_event = $_POST['end_event'];
    $tempat_event = $_POST['tempat_event'];
    $alamat_event = $_POST['alamat_event'];
    $template = $_POST['template'];
    $id = $_SESSION['id'];

    $logo_event = upload();
    $logo_event2 = upload2();

    if(!$logo_event || !$logo_event2) {
        return false;
    }

    // Query untuk menyimpan undangan
    $sql = "INSERT INTO plus (judul_undangan, nama_event, desc_event, logo_event, logo_event2, start_event, end_event, tempat_event, alamat_event, template, id)
            VALUES ('$judul_undangan', '$nama_event', '$desc_event', '$logo_event', '$logo_event2', '$start_event', '$end_event', '$tempat_event', '$alamat_event', '$template', '$id')";

    if ($conn->query($sql) === TRUE) {
        $plus_id = $conn->insert_id; // Ambil ID terakhir yang dimasukkan

        // Proses upload banyak gambar dokumentasi
        if (!empty($_FILES['documentation_event']['name'][0])) {
            $documentation_files = $_FILES['documentation_event'];
            foreach ($documentation_files['name'] as $index => $filename) {
                $tmpName = $documentation_files['tmp_name'][$index];
                $uploadedFile = uploadMultiple($filename, $tmpName);

                if ($uploadedFile) {
                    // Masukkan ke tabel dokumentasi
                    $sql_dokumentasi = "INSERT INTO dokumentasi (fid_undangan, image) VALUES ($plus_id, '$uploadedFile')";
                    if ($conn->query($sql_dokumentasi)) {
                        $dokumentasi_id = $conn->insert_id;

                        $sql_image = "INSERT INTO image (image, fid_dokumentasi) VALUES ('$uploadedFile', $dokumentasi_id)";
                        $conn->query($sql_image);
                    }
                }
            }
        }

        $success_message = "Undangan dan dokumentasi berhasil disimpan.";
    } else {
        $error_message = "Error: " . $conn->error;
    }
}

function upload()
{
    if (!isset($_FILES['logo_event'])) {
        return false;
    }

    $namaFile = $_FILES['logo_event']['name'];
    $ukuranFile = $_FILES['logo_event']['size'];
    $error = $_FILES['logo_event']['error'];
    $tmpName = $_FILES['logo_event']['tmp_name'];

    if ($error === 4) {
        return false;
    }

    if ($ukuranFile > 1000000) {
        return false;
    }

    $fileExt = pathinfo($namaFile, PATHINFO_EXTENSION);
    $newFileName = uniqid() . '.' . $fileExt;

    $uploadDir = realpath(__DIR__ . '/../img/image-event') . '/';
    $uploadPath = $uploadDir . $newFileName;

    return move_uploaded_file($tmpName, $uploadPath) ? $newFileName : false;
}

function upload2()
{
    if (!isset($_FILES['logo_event2'])) {
        return false;
    }

    $namaFile = $_FILES['logo_event2']['name'];
    $ukuranFile = $_FILES['logo_event2']['size'];
    $error = $_FILES['logo_event2']['error'];
    $tmpName = $_FILES['logo_event2']['tmp_name'];

    if ($error === 4) {
        return false;
    }

    if ($ukuranFile > 1000000) {
        return false;
    }

    $fileExt = pathinfo($namaFile, PATHINFO_EXTENSION);
    $newFileName = uniqid() . '.' . $fileExt;

    $uploadDir = realpath(__DIR__ . '/../img/image-event') . '/';
    $uploadPath = $uploadDir . $newFileName;

    return move_uploaded_file($tmpName, $uploadPath) ? $newFileName : false;
}


function uploadMultiple($fileName, $tmpName)
{
    if (!$fileName) {
        return false;
    }

    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
    $newFileName = uniqid() . '.' . $fileExt;

    $uploadDir = realpath(__DIR__ . '/../img/documentation') . '/';
    $uploadPath = $uploadDir . $newFileName;

    return move_uploaded_file($tmpName, $uploadPath) ? $newFileName : false;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Undangan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-red-950 flex flex-col items-center justify-center min-h-screen text-white">

    <!-- Tombol Kembali -->
    <div class="absolute top-5 left-5">
        <a href="../admin.php" class="text-white text-2xl hover:text-gray-300">
            <i class="bi bi-arrow-left"></i>
        </a>
    </div>

    <!-- Container Form -->
    <div class="bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-2xl">
        <h2 class="text-2xl font-bold text-center mb-4">Buat Undangan Digital</h2>

        <!-- Form -->
        <form action="tambahundangan.php" method="POST" enctype="multipart/form-data" class="space-y-4">
            
            <div>
                <label class="block text-sm font-medium text-gray-300">Judul Undangan</label>
                <input type="text" name="judul_undangan" class="w-full p-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:ring-red-400 focus:outline-none" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300">Nama Event</label>
                <input type="text" name="nama_event" class="w-full p-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:ring-red-400 focus:outline-none" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300">Logo Event</label>
                <input type="file" name="logo_event" class="w-full text-gray-300 bg-gray-700 border border-gray-600 rounded-lg p-2 focus:ring-red-400 focus:outline-none" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300">Logo Event 2</label>
                <input type="file" name="logo_event2" class="w-full text-gray-300 bg-gray-700 border border-gray-600 rounded-lg p-2 focus:ring-red-400 focus:outline-none" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300">Deskripsi Event</label>
                <textarea name="desc_event" rows="2" class="w-full p-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:ring-red-400 focus:outline-none" required></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300">Documentation Event</label>
                <input type="file" name="documentation_event[]" multiple class="w-full text-gray-300 bg-gray-700 border border-gray-600 rounded-lg p-2 focus:ring-red-400 focus:outline-none" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300">Start Event</label>
                    <input type="datetime-local" name="start_event" class="w-full p-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:ring-red-400 focus:outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300">End Event</label>
                    <input type="datetime-local" name="end_event" class="w-full p-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:ring-red-400 focus:outline-none" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300">Tempat Event</label>
                <input type="text" name="tempat_event" class="w-full p-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:ring-red-400 focus:outline-none" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300">Alamat Event</label>
                <textarea name="alamat_event" rows="2" class="w-full p-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:ring-red-400 focus:outline-none" required></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300">Pilih Template</label>
                <div class="flex justify-center gap-6 mt-2">
                    <div class="cursor-pointer border-2 border-gray-500 rounded-md hover:border-red-400 transition" onclick="setTemplate(1)" id="template1">
                        <img src="../assets/templates/2.png" class="w-28 h-20">
                    </div>
                    <div class="cursor-pointer border-2 border-gray-500 rounded-md hover:border-red-400 transition" onclick="setTemplate(2)" id="template2">
                        <img src="../assets/templates/1.png" class="w-28 h-20">
                    </div>
                    <div class="cursor-pointer border-2 border-gray-500 rounded-md hover:border-red-400 transition" onclick="setTemplate(3)" id="template3">
                        <img src="../assets/templates/3.png" class="w-28 h-20">
                    </div>
                </div>
                <input type="hidden" name="template" id="selected_template" value="0">
            </div>

            <div class="text-center">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold p-2 rounded-lg transition">
                    Simpan Undangan
                </button>
            </div>

            <div class="text-center mt-4">
                <a href="../admin.php" class="text-blue-600 hover:text-blue-500 text-sm">Back</a>
            </div>
        </form>
    </div>

    <script>
        function setTemplate(value) {
            document.getElementById('selected_template').value = value;
            document.querySelectorAll('.cursor-pointer').forEach(el => el.classList.remove('border-red-400'));
            document.getElementById('template' + value).classList.add('border-red-400');
        }
    </script>

</body>

</html>
