<?php
session_start();
require '../service/connection.php';

if (!isset($_SESSION['username'])) {
    header('location: ../auth/login.php');
    exit();
}

$query = "SELECT nama_event FROM plus";
$result = $conn->query($query);

$getEvent = $conn->query("SELECT * FROM plus");

if ($getEvent->num_rows < 1) {
    return redirect("event", "Tambahkan event terlebih dahulu", "error");
}

while ($row = $getEvent->fetch_array()) {
    $nama_events[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $telepon = $_POST['telepon'];
    $id_event = $_POST['event'];
    $level = $_POST['level'];

    $sql = "INSERT INTO send (nama, telepon, plus_id, level)
    VALUES ('$nama', '$telepon', '$id_event', '$level')";

    if ($conn->query($sql) === TRUE) {
        $success_message = "Undangan berhasil dikirim!";
    } else {
        $error_message = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kirim Undangan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-red-950 flex flex-col items-center justify-center min-h-screen text-white">

    <!-- Tombol Kembali -->
    <div class="absolute top-5 left-5">
        <a href="../admin.php" class="text-white text-2xl hover:text-gray-300">
            <i class="bi bi-arrow-left"></i>
        </a>
    </div>

    <!-- Container Form -->
    <div class="bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold text-center mb-6">Kirim Undangan</h2>

        <!-- Form -->
        <form action="" method="POST" class="space-y-4">

            <div>
                <label class="block text-sm font-medium text-gray-300">Nama Tamu</label>
                <input type="text" name="nama" class="w-full p-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:ring-red-400 focus:outline-none" placeholder="Masukkan nama tamu" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300">Nomor Telepon</label>
                <input type="text" name="telepon" class="w-full p-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:ring-red-400 focus:outline-none" placeholder="Masukkan nomor telepon" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300">Pilih Acara</label>
                <select name="event" class="w-full p-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:ring-red-400 focus:outline-none" required>
                    <option value="">-- Pilih Acara --</option>
                    <?php foreach ($nama_events as $event) : ?>
                        <option value="<?= $event['plus_id']?>"><?= $event['judul_undangan']?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300">Pilih Level</label>
                <select name="level" class="w-full p-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:ring-red-400 focus:outline-none" required>
                    <option value="">-- Pilih Level --</option>
                    <option value="VIP">VIP</option>
                    <option value="REGULAR">Regular</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300">Nama Pengirim</label>
                <input type="text" name="nama_pengirim" class="w-full p-2 bg-gray-700 text-white border border-gray-600 rounded-lg focus:ring-red-400 focus:outline-none" placeholder="Masukkan nama pengirim" required>
            </div>

            <div class="text-center">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold p-2 rounded-lg transition">
                    Kirim Undangan
                </button>
            </div>

            <div class="text-center mt-4">
                <a href="../admin.php" class="text-blue-600 hover:text-blue-500 text-sm">Back</a>
            </div>

        </form>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>

    <?php
    include '../service/utility.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // $sql2 = "SELECT * FROM send WHERE fid_undangan = $undangan";
        // Ambil nama event berdasarkan ID
        $query = $conn->prepare("SELECT id FROM send WHERE telepon = ?");
        $query->bind_param("s", $telepon);
        $query->execute();
        $result = $query->get_result();
        $send_data = $result->fetch_assoc();
        $id = $send_data['id'] ?? null;

        $username = $_POST['nama_pengirim'];

        $link = "http://localhost/mkk-undangant/src/activities/undangan.php?id=$id";


        // // Variabel untuk pesan otomatis
        // $username = "Acara Hebat"; // Ganti dengan username Anda
        // $nama_event = "Pesta Pernikahan"; // Ganti dengan nama acara Anda
        // $link = "https://example.com/undangan"; // Ganti dengan link acara Anda

        // Membuat pesan otomatis
        $query = $conn->prepare("SELECT s.id, p.judul_undangan AS events 
                         FROM send s 
                         JOIN plus p ON s.plus_id = p.plus_id 
                         WHERE s.id = ?");
        $query->bind_param("i", $id);
        $query->execute();
        $result = $query->get_result();
        $event_data = $result->fetch_assoc();
        $nama_event = $event_data['events'];


        $pesan = "Halo $nama, kami dari $username turut mengundang Anda di acara $nama_event, silahkan akses melalui link berikut: $link";

        $token = "73iTpmnvV9ntMhpLhE8t";

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'target' => $telepon,
                'message' => $pesan,
            ),
            CURLOPT_HTTPHEADER => array(
                "Authorization: $token"
            ),
        ));

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);

        if (isset($error_msg)) {
            $_SESSION['error'] = $error_msg;
        } else {
            $_SESSION['success'] = 'Undangan terkirim!';
        }

        if (!headers_sent()) {
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "<script>window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
            exit();
        }
    }
    ?>

    <?php
    if (isset($_SESSION['error'])) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '" . $_SESSION['error'] . "',
                showConfirmButton: true
            });
        </script>";
        unset($_SESSION['error']);
    }

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