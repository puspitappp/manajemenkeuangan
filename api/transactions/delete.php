<?php
require_once '../connect.php';

// Periksa apakah parameter ID telah disertakan dalam URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Escape input untuk menghindari SQL Injection
    $id = $conn->real_escape_string($_GET['id']);

    // Buat dan jalankan kueri DELETE untuk menghapus transaksi berdasarkan ID
    $sql = "DELETE FROM transactions WHERE transaction_id = $id";

    if ($conn->query($sql) === TRUE) {
        // Jika penghapusan berhasil, kirim respons JSON
        echo json_encode(array("message" => "Transaksi berhasil dihapus"));
    } else {
        // Jika terjadi kesalahan saat menghapus, kirim respons JSON dengan pesan kesalahan
        echo json_encode(array("message" => "Error: " . $sql . "<br>" . $conn->error));
    }
} else {
    // Jika parameter ID tidak disertakan dalam URL, kirim respons JSON dengan pesan kesalahan
    echo json_encode(array("message" => "ID transaksi tidak valid"));
}

// Tutup koneksi database
$conn->close();
