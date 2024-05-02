<?php
require_once 'connect.php';

// Baca data yang dikirimkan dalam permintaan POST
$data = json_decode(file_get_contents("php://input"), true);

// Periksa apakah data yang diperlukan telah disediakan
if (isset($data['username']) && isset($data['password'])) {
    // Escape input untuk menghindari SQL Injection
    $username = $conn->real_escape_string($data['username']);
    $password = $conn->real_escape_string($data['password']);

    // Buat kueri untuk mencari pengguna dengan username dan password yang sesuai
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";

    // Jalankan kueri
    $result = $conn->query($sql);

    // Periksa apakah pengguna ditemukan
    if ($result->num_rows > 0) {
        // Jika pengguna ditemukan, kirim respons JSON yang menyatakan berhasil login
        echo json_encode(array("message" => "Login berhasil"));
    } else {
        // Jika pengguna tidak ditemukan, kirim respons JSON yang menyatakan gagal login
        echo json_encode(array("message" => "Login gagal. Username atau password salah"));
    }
} else {
    // Jika data yang diperlukan tidak disediakan, kirim respons JSON dengan pesan kesalahan
    echo json_encode(array("message" => "Username dan password diperlukan"));
}

// Tutup koneksi database
$conn->close();
