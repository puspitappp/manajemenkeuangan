<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Edit Transaction | KELOMPOK 7 MIC</title>
    <meta name="description" content="Edit Transaction Page">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/style-global-app.css">
    <style>
        /* Gaya CSS untuk form edit */
        .form-container {
            margin: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-container h2 {
            margin-top: 0;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group input[type="date"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group input[type="submit"] {
            padding: 10px 15px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .form-group input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<?php
    include 'phpscripts/connect.php';

    session_start();

    // Cek apakah ada parameter transaction_id yang diterima dari URL
    if(isset($_GET['transaction_id'])) {
        $transaction_id = $_GET['transaction_id'];

        // Query untuk mendapatkan detail transaksi berdasarkan ID transaksi
        $selectTransaction = "SELECT * FROM `transactions` WHERE `transaction_id` = '$transaction_id'";
        $result = $connection->query($selectTransaction);

        if ($result->num_rows > 0) {
            // Ambil data transaksi dari hasil query
            $row = $result->fetch_assoc();
            $name = $row['name'];
            $type = $row['type'];
            $total_expense = $row['total_expense'];
            $total_income = $row['total_income'];
            $date_added = $row['date_added'];
        } else {
            // Jika tidak ada transaksi dengan ID tersebut, tampilkan pesan kesalahan
            echo "<h2>Transaction not found!</h2>";
            exit();
        }
    } else {
        // Jika tidak ada parameter transaction_id, tampilkan pesan kesalahan
        echo "<h2>Transaction ID not provided!</h2>";
        exit();
    }

    // Handle form submission untuk update transaksi
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Ambil nilai yang diinputkan oleh pengguna
        $new_name = $connection->real_escape_string($_POST['name']);
        $new_type = $connection->real_escape_string($_POST['type']);
        $new_total_expense = $_POST['total_expense'];
        $new_total_income = $_POST['total_income'];
        $new_date_added = $_POST['date_added'];

        // Query untuk mengupdate data transaksi
        $updateQuery = "UPDATE `transactions` SET `name` = '$new_name', `type` = '$new_type', `total_expense` = '$new_total_expense', `total_income` = '$new_total_income', `date_added` = '$new_date_added' WHERE `transaction_id` = '$transaction_id'";
        if ($connection->query($updateQuery) === TRUE) {
            // Jika update berhasil, kembalikan pengguna ke dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            // Jika terjadi kesalahan saat update, tampilkan pesan kesalahan
            echo "<p>Error: " . $connection->error . "</p>";
        }
    }
?>
    <div class="wrapper">
        <!-- Content -->
        <div class="container-content">
            <div class="content-middle">
                <div class="container-content-title">
                    <h2>Edit Transaction</h2>
                </div>
                <!-- Form edit transaction -->
                <div class="form-container">
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="type">Type:</label>
                            <input type="text" id="type" name="type" value="<?php echo htmlspecialchars($type); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="total_expense">Total Expense:</label>
                            <input type="number" id="total_expense" name="total_expense" value="<?php echo $total_expense; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="total_income">Total Income:</label>
                            <input type="number" id="total_income" name="total_income" value="<?php echo $total_income; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="date_added">Date Added:</label>
                            <input type="date" id="date_added" name="date_added" value="<?php echo $date_added; ?>" required>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Update Transaction">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
