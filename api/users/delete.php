<?php
require_once '../connect.php';

// Periksa apakah account_id disertakan dalam URL
if (isset($_GET['account_id'])) {
    // Escape input untuk menghindari SQL Injection
    $account_id = $conn->real_escape_string($_GET['account_id']);

    // Buat kueri DELETE untuk menghapus pengguna dari tabel users berdasarkan account_id
    $sql = "DELETE FROM users WHERE account_id = '$account_id'";

    if ($conn->query($sql) === TRUE) {
        // Jika penghapusan berhasil, kirim respons JSON
        echo json_encode(array("message" => "Pengguna dengan ID $account_id berhasil dihapus"));
    } else {
        // Jika terjadi kesalahan saat menghapus, kirim respons JSON dengan pesan kesalahan
        echo json_encode(array("message" => "Error: " . $sql . "<br>" . $conn->error));
    }
} else {
    // Jika account_id tidak disertakan dalam URL, kirim respons JSON dengan pesan kesalahan
    echo json_encode(array("message" => "account_id diperlukan dalam URL"));
}

// Tutup koneksi database
$conn->close();
