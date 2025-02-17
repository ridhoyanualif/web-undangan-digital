<?php
session_start();
require '../service/connection.php';

if (!isset($_SESSION['username'])) {
    header('location:../auth/login.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data undangan berdasarkan ID
    $sql = "SELECT * FROM plus WHERE plus_id = ? AND id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id, $_SESSION['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $undangan = $result->fetch_assoc();

    if (!$undangan) {
        echo "Data undangan tidak ditemukan!";
        exit();
    }
} else {
    echo "ID undangan tidak disediakan!";
    exit();
}

// Proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul_undangan = $_POST['judul_undangan'];
    $nama_event = $_POST['nama_event'];
    $desc_event = mysqli_real_escape_string($conn, $_POST['desc_event']);
    $start_event = $_POST['start_event'];
    $end_event = $_POST['end_event'];
    $tempat_event = $_POST['tempat_event'];
    $alamat_event = $_POST['alamat_event'];
    $template = $_POST['template'];

    $logo_event = upload('logo_event') ?: $undangan['logo_event'];
    $logo_event2 = upload('logo_event2') ?: $undangan['logo_event2'];

    $updateSql = "UPDATE plus SET judul_undangan = ?, nama_event = ?, logo_event = ?, logo_event2 = ?, desc_event = ?, start_event = ?, end_event = ?, tempat_event = ?, alamat_event = ?, template = ? WHERE plus_id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("ssssssssssi", $judul_undangan, $nama_event, $logo_event, $logo_event2, $desc_event, $start_event, $end_event, $tempat_event, $alamat_event, $template, $id);

    if ($updateStmt->execute()) {
        
        // Proses upload banyak gambar dokumentasi
        if (!empty($_FILES['documentation_event']['name'][0])) {
            $documentation_files = $_FILES['documentation_event'];
            foreach ($documentation_files['name'] as $index => $filename) {
                $tmpName = $documentation_files['tmp_name'][$index];
                $uploadedFile = uploadMultiple($filename, $tmpName);

                if ($uploadedFile) {
                    $sql_dokumentasi = "INSERT INTO dokumentasi (fid_undangan, image) VALUES (?, ?)";
                    $stmt_dokumentasi = $conn->prepare($sql_dokumentasi);
                    $stmt_dokumentasi->bind_param("is", $id, $uploadedFile);
                    $stmt_dokumentasi->execute();
                }
            }
        }

        header("Location: lihatundangan.php?msg=success");
        exit();
    } else {
        echo "Gagal mengupdate undangan!";
    }
}

function upload($inputName)
{
    if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] === 4) {
        return false;
    }

    $namaFile = $_FILES[$inputName]['name'];
    $ukuranFile = $_FILES[$inputName]['size'];
    $tmpName = $_FILES[$inputName]['tmp_name'];

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
    <title>Edit Undangan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-red-950 flex flex-col items-center min-h-screen text-white relative">

    <!-- Tombol Kembali -->
    <div class="absolute top-5 left-5">
        <a href="../activities/lihatundangan.php" class="flex items-center text-white text-lg hover:text-gray-300 transition">
            <i class="bi bi-arrow-left text-2xl"></i>
            <span class="ml-2">Kembali</span>
        </a>
    </div>

    <!-- Container Form -->
    <div class="bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-2xl mt-12">
        <h2 class="text-2xl font-bold text-center mb-6">Edit Undangan</h2>

        <!-- Form -->
        <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
            
            <div>
                <label class="block text-sm font-medium text-gray-300">Judul Undangan</label>
                <input type="text" name="judul_undangan" value="<?= htmlspecialchars($undangan['judul_undangan']); ?>" class="w-full p-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:ring-red-400 focus:outline-none" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300">Nama Event</label>
                <input type="text" name="nama_event" value="<?= htmlspecialchars($undangan['nama_event']); ?>" class="w-full p-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:ring-red-400 focus:outline-none" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300">Logo Event (kosongkan bila tidak ingin mengubah)</label>
                <input type="file" name="logo_event" class="w-full text-gray-300 bg-gray-700 border border-gray-600 rounded-lg p-2 focus:ring-red-400 focus:outline-none" >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300">Logo Event 2 (kosongkan bila tidak ingin mengubah)</label>
                <input type="file" name="logo_event2" class="w-full text-gray-300 bg-gray-700 border border-gray-600 rounded-lg p-2 focus:ring-red-400 focus:outline-none" >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300">Deskripsi Event</label>
                <textarea name="desc_event" rows="2" class="w-full p-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:ring-red-400 focus:outline-none" required><?= htmlspecialchars($undangan['desc_event']); ?></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300">Documentation Event (kosongkan bila tidak ingin mengubah)</label>
                <input type="file" name="documentation_event[]" multiple class="w-full text-gray-300 bg-gray-700 border border-gray-600 rounded-lg p-2 focus:ring-red-400 focus:outline-none" >
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300">Start Event</label>
                    <input type="datetime-local" name="start_event" value="<?= htmlspecialchars($undangan['start_event']); ?>" class="w-full p-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:ring-red-400 focus:outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300">End Event</label>
                    <input type="datetime-local" name="end_event" value="<?= htmlspecialchars($undangan['end_event']); ?>" class="w-full p-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:ring-red-400 focus:outline-none" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300">Tempat Event</label>
                <input type="text" name="tempat_event" value="<?= htmlspecialchars($undangan['tempat_event']); ?>" class="w-full p-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:ring-red-400 focus:outline-none" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300">Alamat Event</label>
                <textarea name="alamat_event" rows="2" class="w-full p-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:ring-red-400 focus:outline-none" required><?= htmlspecialchars($undangan['alamat_event']); ?></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300">Pilih Template</label>
                <div class="flex justify-center gap-4 mt-2">
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
                <input type="hidden" name="template" id="selected_template" value="<?= htmlspecialchars($undangan['template']); ?>">
            </div>

            <div class="text-center">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold p-2 rounded-lg transition">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>

</body>

<script>
    function setTemplate(value) {
        document.getElementById('selected_template').value = value;
        highlightSelected(value);
    }

    function highlightSelected(value) {
        document.getElementById('template1').style.border = value == 1 ? '2px solid red' : '1px solid #ccc';
        document.getElementById('template2').style.border = value == 2 ? '2px solid red' : '1px solid #ccc';
        document.getElementById('template3').style.border = value == 3 ? '2px solid red' : '1px solid #ccc';
    }

    // Pilih template yang sudah tersimpan saat halaman dimuat
    window.onload = function() {
        let selectedTemplate = document.getElementById('selected_template').value;
        if (selectedTemplate) {
            highlightSelected(selectedTemplate);
        }
    };
</script>
</html>
