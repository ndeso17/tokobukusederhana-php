<?php
session_start();
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h1>Keranjang Belanja</h1>
        <a href="index.php" class="btn btn-primary mb-3">Lanjut Belanja</a>
        <?php if (!empty($_SESSION['cart'])): ?>
        <form method="POST" action="cart.php">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Jumlah</th>
                        <th>Harga Satuan</th>
                        <th>Total Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $grand_total = 0;
                    foreach ($_SESSION['cart'] as $id => $jumlah) {
                        $stmt = mysqli_prepare($conn, "SELECT id_buku, judul, harga FROM buku WHERE id_buku = ?");
                        mysqli_stmt_bind_param($stmt, "i", $id);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        $buku = mysqli_fetch_assoc($result);
                        mysqli_stmt_close($stmt);

                        if ($buku) {
                            $total = $buku['harga'] * $jumlah;
                            $grand_total += $total;
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($buku['judul']); ?></td>
                        <td>
                            <input type="number" name="jumlah[<?php echo $id; ?>]" value="<?php echo $jumlah; ?>"
                                min="1" class="form-control w-50">
                        </td>
                        <td>Rp<?php echo number_format($buku['harga'], 0, ',', '.'); ?></td>
                        <td>Rp<?php echo number_format($total, 0, ',', '.'); ?></td>
                        <td>
                            <a href="cart.php?remove=<?php echo $id; ?>" class="btn btn-danger btn-sm">Hapus</a>
                        </td>
                    </tr>
                    <?php
                        } else {
                            // Remove invalid book from cart
                            unset($_SESSION['cart'][$id]);
                        }
                    }
                    ?>
                    <tr>
                        <td colspan="3"><strong>Grand Total</strong></td>
                        <td><strong>Rp<?php echo number_format($grand_total, 0, ',', '.'); ?></strong></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <button type="submit" name="update" class="btn btn-warning">Perbarui Keranjang</button>
            <a href="checkout.php" class="btn btn-success">Checkout</a>
        </form>

        <?php
        // Handle cart updates
        if (isset($_POST['update']) && isset($_POST['jumlah'])) {
            foreach ($_POST['jumlah'] as $id => $jumlah) {
                $jumlah = (int) $jumlah;
                if ($jumlah > 0) {
                    $_SESSION['cart'][$id] = $jumlah;
                } else {
                    unset($_SESSION['cart'][$id]);
                }
            }
            header('Location: cart.php');
            exit;
        }

        // Menghapus item dari keranjang
        if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
            $id = (int) $_GET['remove'];
            unset($_SESSION['cart'][$id]);
            header('Location: cart.php');
            exit;
        }
        ?>
        <?php else: ?>
        <p>Keranjang kosong.</p>
        <?php endif; ?>
    </div>
</body>

</html>