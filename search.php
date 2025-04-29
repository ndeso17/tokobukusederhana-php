<?php
include 'koneksi.php';

$cari = isset($_GET['cari']) ? trim($_GET['cari']) : '';

$sql = "SELECT * FROM buku";
if (!empty($cari)) {
    $sql .= " WHERE judul LIKE ?";
}

$stmt = mysqli_prepare($conn, $sql);
if (!empty($cari)) {
    $searchTerm = "%$cari%";
    mysqli_stmt_bind_param($stmt, "s", $searchTerm);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    while ($buku = mysqli_fetch_assoc($result)) {
        echo '
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm">
                <img src="https://placehold.co/150x200?text=Cover+Buku" class="card-img-top" alt="Cover Buku">
                <div class="card-body">
                    <h5 class="card-title">
                        <a href="detail.php?id=' . $buku['id_buku'] . '" class="text-decoration-none">'
                            . htmlspecialchars($buku['judul']) . '
                        </a>
                    </h5>
                    <p class="card-text">
                        <strong>Harga:</strong> Rp' . number_format($buku['harga'], 0, ',', '.') . '
                    </p>
                </div>
                <div class="card-footer text-center">
                    <a href="beli.php?id=' . $buku['id_buku'] . '" class="btn btn-primary">Tambah ke Keranjang</a>
                </div>
            </div>
        </div>';
    }
} else {
    echo '<p class="text-center">Tidak ada buku ditemukan.</p>';
}
?>