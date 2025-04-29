<?php
function loadEnv($path) {
    if (!file_exists($path)) {
        
        $path = dirname($path) . '/.envs';
        if (!file_exists($path)) {
            return; 
        }
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue; 
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value, "\"'"); 
    }
}

// Pakai:
loadEnv(__DIR__ . '/.env');

// Lanjut koneksi DB
$conn = mysqli_connect(
    $_ENV['DB_HOST'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS'],
    $_ENV['DB_NAME']
);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>