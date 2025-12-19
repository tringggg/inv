<?php
include 'db.php';

// Fetch all sales with item name
$query = "SELECT s.id AS sale_id, i.item_name, s.quantity, s.total_price, s.sale_date
          FROM sales s
          JOIN items i ON s.item_id = i.id
          ORDER BY s.sale_date DESC";

$result = $connect->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sales List - Inventory & Sales Management System</title>
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
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .top-actions div {
            display: flex;
            gap: 10px;
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
            flex-grow: 1;
            text-align: center;
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

        a.action-btn {
            text-decoration: none;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            margin: 2px;
            display: inline-block;
            transition: 0.3s;
        }

        .btn-edit { background: #ffc107; }
        .btn-delete { background: #dc3545; }

        .btn-edit:hover { background: #ffca2c; }
        .btn-delete:hover { background: #ff4d4d; }

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
        <div>
            <a href="index.php" class="btn">🏠 Home</a>
            <a href="add_sale.php" class="btn">+ Add New Sale</a>
        </div>
        <h2>Sales List</h2>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Item Name</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Sale Date</th>
            <th>Actions</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['sale_id'] ?></td>
                    <td><?= htmlspecialchars($row['item_name']) ?></td>
                    <td><?= $row['quantity'] ?></td>
                    <td>$<?= number_format($row['total_price'], 2) ?></td>
                    <td><?= htmlspecialchars($row['sale_date']) ?></td>
                    <td>
                        <a class="action-btn btn-edit" href="update_sale.php?id=<?= $row['sale_id'] ?>">Edit</a>
                        <a class="action-btn btn-delete" href="delete_sale.php?id=<?= $row['sale_id'] ?>" onclick="return confirm('Are you sure you want to delete this sale?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">No sales found.</td>
            </tr>
        <?php endif; ?>
    </table>
</div>

<footer>© <?= date("Y") ?> Inventory & Sales Management System</footer>

</body>
</html>
