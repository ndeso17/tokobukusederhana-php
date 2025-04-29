<?php
include 'koneksi.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = (int) $_GET['id'];

// Join database untuk mendapatkan detail buku
$stmt = mysqli_prepare($conn, "SELECT b.id_buku, b.judul, b.harga, p.nama AS penulis 
                               FROM buku b 
                               JOIN penulis p ON b.id_penulis = p.id_penulis 
                               WHERE b.id_buku = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$buku = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$buku) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Detail Buku - <?php echo htmlspecialchars($buku['judul']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <a href="index.php" class="btn btn-secondary mb-3">Kembali</a>
        <div class="row">
            <div class="col-md-4">
                <img src="https://placehold.co/300x400?text=Cover+Buku" class="img-fluid" alt="Cover Buku">
            </div>
            <div class="col-md-8">
                <h2><?php echo htmlspecialchars($buku['judul']); ?></h2>
                <p><strong>Penulis:</strong> <?php echo htmlspecialchars($buku['penulis']); ?></p>
                <p><strong>Harga:</strong> Rp<?php echo number_format($buku['harga'], 0, ',', '.'); ?></p>
                <a href="beli.php?id=<?php echo $buku['id_buku']; ?>" class="btn btn-success">Tambah ke Keranjang</a>
            </div>
        </div>
    </div>
</body>

</html>