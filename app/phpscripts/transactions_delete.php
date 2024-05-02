<?php
    // Include file koneksi ke database
    include 'connect.php';

    // Pastikan permintaan POST memiliki data transaction_id
    if(isset($_POST['transaction_id'])) {
        $transaction_id = $_POST['transaction_id'];
        
        // Lakukan operasi delete di database
        $delete_query = "DELETE FROM `transactions` WHERE `transaction_id` = '$transaction_id'";
        $connection->query($delete_query);

        // Berikan respons atau feedback kepada pengguna
        echo "Transaksi berhasil dihapus.";
    }
?>
