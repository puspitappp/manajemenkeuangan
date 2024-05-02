<?php
require_once '../connect.php';

// Periksa apakah parameter ID telah disertakan dalam URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Escape input untuk menghindari SQL Injection
    $id = $conn->real_escape_string($_GET['id']);

    // Baca data dari body request (format JSON)
    $data = json_decode(file_get_contents("php://input"), true);

    // Pastikan data yang dibutuhkan telah disertakan dalam body request
    if (isset($data['name']) && isset($data['type']) && isset($data['total_expense']) && isset($data['total_income'])) {
        // Escape input untuk menghindari SQL Injection
        $name = $conn->real_escape_string($data['name']);
        $type = $conn->real_escape_string($data['type']);
        $total_expense = $conn->real_escape_string($data['total_expense']);
        $total_income = $conn->real_escape_string($data['total_income']);

        // Buat dan jalankan kueri UPDATE untuk memperbarui transaksi
        $sql = "UPDATE transactions SET name='$name', type='$type', total_expense=$total_expense, total_income=$total_income WHERE transaction_id=$id";

        if ($conn->query($sql) === TRUE) {
            // Jika pembaruan berhasil, kirim respons JSON
            echo json_encode(array("message" => "Transaksi berhasil diperbarui"));
        } else {
            // Jika terjadi kesalahan saat memperbarui, kirim respons JSON dengan pesan kesalahan
            echo json_encode(array("message" => "Error: " . $sql . "<br>" . $conn->error));
        }
    } else {
        // Jika data yang dibutuhkan tidak disertakan dalam body request, kirim respons JSON dengan pesan kesalahan
        echo json_encode(array("message" => "Nama, jenis, total pengeluaran, dan total pendapatan diperlukan"));
    }
} else {
    // Jika parameter ID tidak disertakan dalam URL, kirim respons JSON dengan pesan kesalahan
    echo json_encode(array("message" => "ID transaksi tidak valid"));
}

// Tutup koneksi database
$conn->close();
