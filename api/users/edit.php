<?php
require_once '../connect.php';

// Periksa apakah account_id disertakan dalam URL
if (isset($_GET['account_id'])) {
    // Escape input untuk menghindari SQL Injection
    $account_id = $conn->real_escape_string($_GET['account_id']);

    // Baca data pengguna yang ingin diperbarui dari permintaan JSON
    $data = json_decode(file_get_contents("php://input"), true);

    // Periksa apakah data yang diperlukan telah disediakan
    if (isset($data['first_name']) && isset($data['last_name']) && isset($data['birth_date']) && isset($data['username']) && isset($data['email_address']) && isset($data['password'])) {
        // Escape input untuk menghindari SQL Injection
        $first_name = $conn->real_escape_string($data['first_name']);
        $last_name = $conn->real_escape_string($data['last_name']);
        $birth_date = $conn->real_escape_string($data['birth_date']);
        $username = $conn->real_escape_string($data['username']);
        $email_address = $conn->real_escape_string($data['email_address']);
        $password = $conn->real_escape_string($data['password']);

        // Buat kueri UPDATE untuk memperbarui data pengguna
        $sql = "UPDATE users SET first_name = '$first_name', last_name = '$last_name', birth_date = '$birth_date', username = '$username', email_address = '$email_address', password = '$password' WHERE account_id = '$account_id'";

        if ($conn->query($sql) === TRUE) {
            // Jika pembaruan berhasil, kirim respons JSON
            echo json_encode(array("message" => "Data pengguna dengan ID $account_id berhasil diperbarui"));
        } else {
            // Jika terjadi kesalahan saat memperbarui, kirim respons JSON dengan pesan kesalahan
            echo json_encode(array("message" => "Error: " . $sql . "<br>" . $conn->error));
        }
    } else {
        // Jika data yang diperlukan tidak disediakan, kirim respons JSON dengan pesan kesalahan
        echo json_encode(array("message" => "Semua data pengguna diperlukan"));
    }
} else {
    // Jika account_id tidak disertakan dalam URL, kirim respons JSON dengan pesan kesalahan
    echo json_encode(array("message" => "account_id diperlukan dalam URL"));
}

// Tutup koneksi database
$conn->close();
