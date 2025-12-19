<?php
include 'db.php';

// Fetch all sales with item info
$query = "
    SELECT s.id AS sale_id, i.item_name, s.quantity, s.total_price, s.sale_date, s.created_at
    FROM sales s
    JOIN items i ON s.item_id = i.id
    ORDER BY s.created_at DESC
";

$result = $connect->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Transaction History - Inventory & Sales Management System</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #111;
            font-family: Arial, sans-serif;
            color: white;
        }

        header {
            background: #1e90ff;
            padding: 20px;
            text-align: center;
            font-size: 32px;
            font-weight: bold;
        }

        .container {
            width: 95%;
            max-width: 1000px;
            margin: 40px auto;
        }

        .top-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #1a73e8;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            transition: 0.3s;
        }

        .btn:hover {
            background: #4285f4;
        }

        h2 {
            font-size: 28px;
            margin-bottom: 10px;
            text-transform: uppercase;
            border-bottom: 2px solid #1e90ff;
            padding-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #1c1c1c;
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #333;
        }

        th {
            background: #1e90ff;
        }

        tr:hover {
            background: #333;
        }

        footer {
            margin-top: 50px;
            text-align: center;
            color: #777;
            font-size: 14px;
        }
    </style>
</head>
<body>

<header>💰 INVENTORY & SALES MANAGEMENT</header>

<div class="container">
    <div class="top-actions">
        <h2>Transaction History</h2>
        <a href="index.php" class="btn">🏠 Home</a>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Item Name</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Sale Date</th>
            <th>Created At</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['sale_id'] ?></td>
                    <td><?= htmlspecialchars($row['item_name']) ?></td>
                    <td><?= $row['quantity'] ?></td>
                    <td>$<?= number_format($row['total_price'], 2) ?></td>
                    <td><?= htmlspecialchars($row['sale_date']) ?></td>
                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">No transactions found.</td>
            </tr>
        <?php endif; ?>
    </table>
</div>

<footer>© <?= date("Y") ?> Inventory & Sales Management System</footer>

</body>
</html>
