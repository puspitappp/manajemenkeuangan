<?php
require_once '../connect.php';

// Baca data dari body request (format JSON)
$data = json_decode(file_get_contents("php://input"), true);

// Pastikan data yang dibutuhkan telah disertakan dalam body request
if (isset($data['first_name']) && isset($data['last_name']) && isset($data['birth_date']) && isset($data['username']) && isset($data['email_address']) && isset($data['password'])) {
    // Escape input untuk menghindari SQL Injection
    $first_name = $conn->real_escape_string($data['first_name']);
    $last_name = $conn->real_escape_string($data['last_name']);
    $birth_date = $conn->real_escape_string($data['birth_date']);
    $username = $conn->real_escape_string($data['username']);
    $email_address = $conn->real_escape_string($data['email_address']);
    $password = $conn->real_escape_string($data['password']);

    // Buat kueri INSERT untuk menyisipkan data pengguna baru ke dalam tabel users
    $sql = "INSERT INTO users (first_name, last_name, birth_date, username, email_address, password) VALUES ('$first_name', '$last_name', '$birth_date', '$username', '$email_address', '$password')";

    if ($conn->query($sql) === TRUE) {
        // Jika penyisipan berhasil, kirim respons JSON
        echo json_encode(array("message" => "Pengguna berhasil ditambahkan"));
    } else {
        // Jika terjadi kesalahan saat menyisipkan, kirim respons JSON dengan pesan kesalahan
        echo json_encode(array("message" => "Error: " . $sql . "<br>" . $conn->error));
    }
} else {
    // Jika data yang dibutuhkan tidak disertakan dalam body request, kirim respons JSON dengan pesan kesalahan
    echo json_encode(array("message" => "Nama depan, nama belakang, tanggal lahir, username, alamat email, dan kata sandi diperlukan"));
}

// Tutup koneksi database
$conn->close();
