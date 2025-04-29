<?php
include 'koneksi.php';
session_start();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Toko Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Tambahkan jQuery -->
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php">Toko Buku</a>
            <form class="d-flex" id="searchForm">
                <input class="form-control me-2" type="search" id="searchInput" name="cari" placeholder="Cari buku...">
                <a href="cart.php" class="btn btn-light ms-3">Keranjang</a>
            </form>
        </div>
    </nav>

    <div class="container">
        <h1 class="mb-4">Daftar Buku</h1>
        <div class="row" id="bukuList">
            <!-- Buku-buku akan dimuat disini -->
        </div>
    </div>

    <script>
    $(document).ready(function() {
        function loadBuku(query = '') {
            $.ajax({
                url: "search.php",
                method: "GET",
                data: {
                    cari: query
                },
                success: function(data) {
                    $('#bukuList').html(data);
                }
            });
        }

        // Pertama kali load semua buku
        loadBuku();

        // Saat user mengetik
        $('#searchInput').on('keyup', function() {
            var keyword = $(this).val();
            loadBuku(keyword);
        });
    });
    </script>
</body>

</html>