<?php
require_once "../connect.php";
$data = json_decode(file_get_contents('php://input'), true);

// Menyiapkan pernyataan SQL untuk menyisipkan data ke tabel transactions
$stmt = $conn->prepare("INSERT INTO transactions (name, type, total_expense, total_income, date_added, account_id) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssiii", $name, $type, $total_expense, $total_income, $date_added, $account_id);

// Memasukkan data ke dalam variabel
$name = $data['name'];
$type = $data['type'];
$total_expense = $data['total_expense'];
$total_income = $data['total_income'];
$date_added = date('Y-m-d'); // Menggunakan tanggal hari ini
$account_id = $data['account_id'];

// Menjalankan pernyataan SQL untuk menyisipkan data
if ($stmt->execute()) {
    echo json_encode(array("message" => "Data transaksi berhasil disisipkan."));
} else {
    echo json_encode(array("message" => "Gagal menyisipkan data transaksi: " . $conn->error));
}

// Menutup koneksi database
$stmt->close();
$conn->close();
