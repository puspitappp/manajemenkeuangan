<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dashboard | KELOMPOK 7 MIC</title>
    <meta name="description" content="Dashboard Page">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/style-global-app.css">
    <link rel="stylesheet" type="text/css" href="css/style-app-dashboard.css">
    <style>
        /* Gaya CSS baru untuk filter waktu */
        .date-filter {
            display: flex;
            align-items: center;
            margin-bottom: 20px; /* Menambahkan margin bawah untuk memberi ruang di bawah filter */
        }

        .date-input {
            margin-right: 10px;
            padding: 8px; /* Mengubah padding untuk membuat input lebih besar */
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .filter-btn {
            padding: 8px 15px; /* Mengatur padding tombol */
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s; /* Menambahkan transisi untuk perubahan warna tombol */
        }

        .filter-btn:hover {
            background-color: #0056b3; /* Mengubah warna tombol saat dihover */
        }

        .delete-btn, .edit-btn {
            background-color: #ff0000;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 5px; /* Memberi ruang antara tombol */
        }

        .edit-btn {
            background-color: #007bff; /* Warna untuk tombol Edit */
        }
    </style>
</head>
<body>
<?php
    include 'phpscripts/connect.php';

    session_start();

    $id = $_SESSION['currentid'];

    // Handle delete request
    if(isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['transaction_id'])) {
        $transaction_id = $_GET['transaction_id'];
        // Perform delete query
        $deleteQuery = "DELETE FROM `transactions` WHERE `transaction_id` = '$transaction_id' AND `account_id` = '$id'";
        $connection->query($deleteQuery);
        // Redirect back to dashboard after delete
        header("Location: dashboard.php");
        exit();
    }

    // Filter by date range if provided
    $dateFilterQuery = '';
    if(isset($_GET['start_date']) && isset($_GET['end_date'])) {
        $start_date = $_GET['start_date'];
        $end_date = $_GET['end_date'];
        $dateFilterQuery = " AND `date_added` BETWEEN '$start_date' AND '$end_date'";
    }

    $selectTransaction = "SELECT * FROM `transactions` WHERE `account_id` = '$id'$dateFilterQuery";

    $userQuery = $connection->query($selectTransaction);

    $addedPanes = array();
    $chartData = array();
    $typeList = array();
    
    if ($userQuery->num_rows > 0) {
        while($row = $userQuery->fetch_assoc()){
            $transaction_id = $row['transaction_id'];
            $name = $row['name'];
            $type = $row['type'];
            $income = $row['total_income'];
            $expense = $row['total_expense'];
            $date = $row['date_added'];

            $typeList[] = $type;

            $addedElement = "<li>
                                <div class='pane-content'>
                                    <div class='container-content-data'>
                                        <h2>$name</h2>
                                        <table id='tb-transaction' cellpadding='10' cellspacing='0'>
                                            <tr>
                                                <th class='type'>Type</th>
                                                <th class='income'>Income</th>
                                                <th class='expense'>Expense</th>
                                                <th class='actions'>Actions</th> <!-- Tambah kolom untuk tombol Edit -->
                                            </tr>

                                            <tr>
                                                <td>$type</td>
                                                <td style='color: #089908'>Rp $income</td>
                                                <td style='color: #ff0000'>Rp $expense</td>
                                                <td>
                                                    <!-- Tombol Edit -->
                                                    <button class='edit-btn' onclick='editTransaction($transaction_id)'>Edit</button>
                                                    <!-- Tombol Delete -->
                                                    <button class='delete-btn' onclick='deleteTransaction($transaction_id)'>Delete</button>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <h3>Date added: <span class='unbold'>$date</span></h3>
                                </div>
                            <li>";
            
            $addedPanes[] = $addedElement;
        }
        
        // Generate chart data
        $chartData = array_count_values($typeList);
        $chartCasting = array();
        foreach($chartData as $x => $x_value) {
            $chartCasting[] = array($x, $x_value);
        }
        echo "<script>
                var chartArray = ".json_encode($chartCasting)."
            </script>";
    }

    function deleteTransaction($transaction_id) {
        // Redirect to self with delete action and transaction_id parameter
        echo "<script>window.location = 'dashboard.php?action=delete&transaction_id=$transaction_id';</script>";
    }
?>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="container-sidebar">
            <div class="container-sidebar-logo">
                <a href="" class="logo-sidebar">KELOMPOK 7 MIC</a>
            </div>
            <div class="nav-and-acc">
                <ul>
                    <li>
                        <button onclick="location.href='dashboard.php'" class="btn-sidebar btn-sidebar-active">
                            <img src="img/icons/bar-chart-active.png" class="icons-sidebar">Dashboard</button>
                    </li>
                    <li>
                        <button onclick="location.href='transaction.html'" class="btn-sidebar">
                            <img src="img/icons/payment.png" class="icons-sidebar">Transaction</button>
                    </li>
                    <li>
                        <button class="btn-sidebar">
                            <img src="img/icons/user.png" class="icons-sidebar">Account</button>
                    </li>
                    <li>
                        <button onclick="location.href='login.php'" class="btn-sidebar">
                            <img src="img/icons/logout.png" class="icons-sidebar">Sign Out</button>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Content -->
        <div class="container-content">
            <div class="content-top">
                <!-- Filter waktu ditempatkan di sini -->
                <div class="date-filter">
                    <form method="GET" action="">
                        <label for="start_date">Start Date:</label>
                        <input type="date" id="start_date" name="start_date" class="date-input">
                        <label for="end_date">End Date:</label>
                        <input type="date" id="end_date" name="end_date" class="date-input">
                        <button type="submit" class="filter-btn">Filter</button>
                    </form>
                </div>
            </div>
            <div class="content-middle">
                <div class="container-content-title">
                    <h2>Dashboard</h2>
                    <p>Overview Your Transactions.</p>
                </div>
                <h2><?php if($userQuery->num_rows == 0){echo "You haven't made any transactions.";}?></h2>
                <ul class="list-transactions">
                    <?php foreach($addedPanes as $display){echo $display;} ?>
                </ul>
            </div>
            <div class="content-right">
                <div class="pane-content">
                    <!-- Pie Chart Goes Here -->
                    <div id="chart_div"></div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(draw_my_chart);

        function draw_my_chart() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Type');
            data.addColumn('number', 'Count');
            for(i = 0; i < chartArray.length; i++){
                data.addRow([chartArray[i][0], chartArray[i][1]]);
            }

            var options = {
                title: 'Transaction Distribution',
                titleTextStyle: {fontSize: 18},
                width: 360,
                height: 390,
                pieHole: 0.6,
                legend: {position: 'bottom', maxLines: 10, textStyle: {fontSize: 12}},
            };

            var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }

        // Function to delete transaction
        function deleteTransaction(transaction_id) {
            // Redirect to self with delete action and transaction_id parameter
            window.location = 'dashboard.php?action=delete&transaction_id=' + transaction_id;
        }

        // Function to edit transaction
        function editTransaction(transaction_id) {
            window.location.href = 'transactions_update.php?transaction_id=' + transaction_id;
            // You can implement the logic for editing transaction here
            // For example, redirect to an edit page with transaction_id parameter
            // Example: window.location = 'edit_transaction.php?transaction_id=' + transaction_id;
        }
    </script>
</body>
</html>
