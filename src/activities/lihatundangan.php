<?php
session_start();
require '../service/connection.php';

if (!isset($_SESSION['username'])) {
    header('location: ../auth/login.php');
    exit();
}

$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

// Query untuk mengambil data undangan berdasarkan pencarian
$sql = "SELECT * FROM plus WHERE id = ? AND (judul_undangan LIKE ? OR nama_event LIKE ? OR start_event LIKE ? OR tempat_event LIKE ?)";
$stmt = $conn->prepare($sql);
$searchParam = "%$search%";
$stmt->bind_param("issss", $_SESSION['id'], $searchParam, $searchParam, $searchParam, $searchParam);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Undangan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-red-950 flex flex-col items-center min-h-screen text-white">

<div class="absolute top-5 left-5">
        <a href="../admin.php" class="flex items-center text-white text-lg hover:text-gray-300 transition">
            <i class="bi bi-arrow-left text-2xl"></i>
            <span class="ml-2">Kembali</span>
        </a>
    </div>

    <!-- Container -->
    <div class="container mx-auto p-6">
        <h3 class="text-3xl font-bold text-center mb-6">Daftar Undangan Digital</h3>

        <!-- Search Bar -->
        <form action="lihatundangan.php" method="GET" class="flex justify-center mb-6">
            <input type="text" name="search" class="w-80 p-2 rounded-l-lg bg-gray-700 text-white border border-gray-600 focus:ring-red-400 focus:outline-none" placeholder="Cari berdasarkan judul, event, atau tanggal" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white p-2 rounded-r-lg">
                <i class="bi bi-search"></i> Cari
            </button>
        </form>

        <!-- Grid Undangan -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>

                    <div class="bg-gray-800 p-4 rounded-lg shadow-lg">
                        <img src="../img/image-event/<?php echo htmlspecialchars($row['logo_event']); ?>" class="w-full h-52 object-cover rounded-md mb-4" alt="Undangan Image">
                        <h5 class="text-xl font-bold"><?php echo htmlspecialchars($row['judul_undangan']); ?></h5>
                        <p class="text-gray-300 text-sm">
                            <strong>Event:</strong> <?php echo htmlspecialchars($row['nama_event']); ?><br>
                            <strong>Tanggal:</strong> <?php echo htmlspecialchars(date('Y-m-d', strtotime($row['start_event']))); ?><br>
                            <strong>Waktu:</strong> <?php echo htmlspecialchars(date('H:i', strtotime($row['start_event']))); ?><br>
                            <strong>Tempat:</strong> <?php echo htmlspecialchars($row['tempat_event']); ?><br>
                        </p>

                        <div class="mt-4 flex gap-2">
                            <a href="../activities/previewundangan.php?id=<?= $row['plus_id']; ?>" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md text-sm">Detail</a>
                            <a href="editundangan.php?id=<?= $row['plus_id']; ?>" class="bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded-md text-sm">Edit</a>
                            <a href="deleteundangan.php?id=<?= $row['plus_id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus undangan ini?')" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-md text-sm">Hapus</a>
                        </div>
                    </div>

                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-span-3 text-center">
                    <div class="bg-gray-800 p-4 rounded-lg shadow-lg">
                        <p class="text-gray-300">Tidak ada undangan yang ditemukan.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>
