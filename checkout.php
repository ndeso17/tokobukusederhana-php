<?php
session_start();
include 'koneksi.php';

// Simulasi login pelanggan, id_pelanggan
$id_pelanggan = 1; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi dan sanitize input
    $alamat_penagihan = mysqli_real_escape_string($conn, $_POST['alamat_penagihan']);
    $alamat_pengiriman = mysqli_real_escape_string($conn, $_POST['alamat_pengiriman']);
    $opsi_pengiriman = mysqli_real_escape_string($conn, $_POST['opsi_pengiriman']);
    $pembayaran = mysqli_real_escape_string($conn, $_POST['pembayaran']);

    // Masukkan data ke tabel checkout
    $stmt = mysqli_prepare($conn, "INSERT INTO checkout (id_pelanggan, alamat_penagihan, alamat_pengiriman, opsi_pengiriman, pembayaran) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "issss", $id_pelanggan, $alamat_penagihan, $alamat_pengiriman, $opsi_pengiriman, $pembayaran);
    mysqli_stmt_execute($stmt);
    $id_checkout = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt);

    // Masukkan data item ke tabel cart
    foreach ($_SESSION['cart'] as $id_buku => $jumlah) {
        $stmt = mysqli_prepare($conn, "SELECT harga FROM buku WHERE id_buku = ?");
        mysqli_stmt_bind_param($stmt, "i", $id_buku);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $buku = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if ($buku) {
            $total = $buku['harga'] * $jumlah;
            $stmt = mysqli_prepare($conn, "INSERT INTO cart (id_buku, total, id_pelanggan) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "isi", $id_buku, $jumlah, $id_pelanggan);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    // Bersihkan cart
    unset($_SESSION['cart']);
    $success = true;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <?php if (isset($success)): ?>
        <h1>Checkout Berhasil!</h1>
        <p>Terima kasih sudah berbelanja di Toko Buku kami.</p>
        <a href="index.php" class="btn btn-primary">Kembali ke Beranda</a>
        <?php else: ?>
        <h1>Checkout</h1>
        <?php if (empty($_SESSION['cart'])): ?>
        <p>Keranjang kosong. Silakan tambah buku ke keranjang.</p>
        <a href="index.php" class="btn btn-primary">Kembali ke Beranda</a>
        <?php else: ?>
        <form method="POST">
            <div class="mb-3">
                <label for="alamat_penagihan" class="form-label">Alamat Penagihan</label>
                <textarea class="form-control" id="alamat_penagihan" name="alamat_penagihan" required></textarea>
            </div>
            <div class="mb-3">
                <label for="alamat_pengiriman" class="form-label">Alamat Pengiriman</label>
                <textarea class="form-control" id="alamat_pengiriman" name="alamat_pengiriman" required></textarea>
            </div>
            <div class="mb-3">
                <label for="opsi_pengiriman" class="form-label">Opsi Pengiriman</label>
                <select class="form-select" id="opsi_pengiriman" name="opsi_pengiriman" required>
                    <option value="JNE Reguler">JNE Reguler</option>
                    <option value="SiCepat Express">SiCepat Express</option>
                    <option value="J&T Express">J&T Express</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="pembayaran" class="form-label">Metode Pembayaran</label>
                <select class="form-select" id="pembayaran" name="pembayaran" required>
                    <option value="Bank Transfer">Bank Transfer</option>
                    <option value="Credit Card">Credit Card</option>
                    <option value="Cash on Delivery">Cash on Delivery</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Konfirmasi Checkout</button>
        </form>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</body>

</html>