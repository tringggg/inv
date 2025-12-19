<?php
include 'db.php';

// Fetch all items
$result = $connect->query("SELECT * FROM items ORDER BY id ASC");

// Check for query error
if (!$result) {
    die("Query failed: " . $connect->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Items List - Inventory Management System</title>
    <style>
        body { margin:0; padding:0; background:#111; font-family:Arial,sans-serif; color:white; }
        header { background:#1e90ff; padding:20px; text-align:center; font-size:32px; font-weight:bold; }
        .container { width:90%; max-width:1000px; margin:40px auto; }
        table { width:100%; border-collapse:collapse; background:#1c1c1c; border-radius:10px; overflow:hidden; }
        th, td { padding:12px; text-align:center; border-bottom:1px solid #333; }
        th { background:#1e90ff; }
        tr:hover { background:#333; }
        a { text-decoration:none; color:white; padding:5px 10px; border-radius:5px; }
        .btn-edit { background:#ffc107; }
        .btn-delete { background:#dc3545; }
        .btn-info { background:#1a73e8; }
        .btn-edit:hover { background:#ffca2c; }
        .btn-delete:hover { background:#ff4d4d; }
        .btn-info:hover { background:#4285f4; }
        .top-actions { margin-bottom:20px; display:flex; justify-content:space-between; align-items:center; }
        .btn-add { background:#1a73e8; padding:10px 20px; border-radius:8px; }
        .btn-add:hover { background:#4285f4; }
        .btn-home { background:#1e90ff; padding:10px 20px; border-radius:8px; margin-left:10px; }
        .btn-home:hover { background:#3399ff; }
        footer { margin-top:50px; text-align:center; color:#777; font-size:14px; }
    </style>
</head>
<body>

<header>📦 INVENTORY MANAGEMENT SYSTEM</header>

<div class="container">
    <div class="top-actions">
        <h2>Items List</h2>
        <div>
            <a href="add_item.php" class="btn-add">+ Add New Item</a>
            <a href="index.php" class="btn-home">🏠 Home</a>
        </div>
    </div>

    <table>
        <tr>
            <th>ID</th><th>Item Name</th><th>Category</th><th>Quantity</th><th>Price ($)</th><th>Actions</th>
        </tr>
        <?php if($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['item_name']) ?></td>
                    <td><?= htmlspecialchars($row['category']) ?></td>
                    <td><?= $row['quantity'] ?></td>
                    <td><?= number_format($row['price'], 2) ?></td>
                    <td>
                        <a class="btn-info" href="inquire_item.php?id=<?= $row['id'] ?>">Info</a>
                        <a class="btn-edit" href="update_item.php?id=<?= $row['id'] ?>">Edit</a>
                        <a class="btn-delete" href="delete_item.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6">No items found.</td></tr>
        <?php endif; ?>
    </table>
</div>

<footer>© <?= date("Y") ?> Inventory Management System</footer>

</body>
</html>
