<?php
include 'db.php';

// Get total sales
$total_sales = 0;
$result = $connect->query("SELECT SUM(total_price) AS total_sales FROM sales");
if ($result) {
    $row = $result->fetch_assoc();
    $total_sales = $row['total_sales'] ?? 0;
}

// Get total inventory value
$total_inventory = 0;
$result = $connect->query("SELECT SUM(quantity * price) AS inventory_value FROM items");
if ($result) {
    $row = $result->fetch_assoc();
    $total_inventory = $row['inventory_value'] ?? 0;
}

$balance = $total_sales - $total_inventory;

$connect->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inventory Balance Inquiry</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #fff;
            color: #000;
            text-align: center;
            margin-top: 100px;
        }

        h1 {
            font-size: 28px;
            margin-bottom: 50px;
        }

        a {
            display: block;
            margin: 15px 0;
            font-size: 20px;
            text-decoration: none;
            color: #0000EE;
        }

        a:hover {
            text-decoration: underline;
        }

        .amount {
            font-weight: bold;
        }
    </style>
</head>
<body>

<h1>Inventory Balance Inquiry</h1>

<a href="#">Total Sales: ₱ <span class="amount"><?= number_format($total_sales, 2) ?></span></a>
<a href="#">Total Inventory Value: ₱ <span class="amount"><?= number_format($total_inventory, 2) ?></span></a>
<a href="#">Current Balance: ₱ <span class="amount"><?= number_format($balance, 2) ?></span></a>

<a href="index.php">⬅ Back to Inventory Dashboard</a>

</body>
</html>
