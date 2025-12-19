<?php
include 'db.php';

$search = '';
$results = [];

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);

    // Fetch items matching search
    $stmt = $connect->prepare(
        "SELECT * FROM items 
         WHERE item_name LIKE ? OR category LIKE ?
         ORDER BY item_name ASC"
    );

    $param = "%" . $search . "%";
    $stmt->bind_param("ss", $param, $param);
    $stmt->execute();
    $results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

$connect->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inquire Item - Inventory Management System</title>
    <style>
        body {margin:0; padding:0; background:#111; color:white; font-family:Arial,sans-serif;}
        header {background:#e50914; padding:20px; text-align:center; font-size:32px; font-weight:bold;}
        .container {width:90%; max-width:900px; margin:40px auto; background:#1c1c1c; padding:20px; border-radius:10px;}
        h2 {text-align:center; margin-bottom:20px; color:#e50914;}
        form input[type="text"], form button {width:100%; padding:10px; margin-bottom:15px; border-radius:5px; border:none; font-size:16px;}
        input[type="text"] {background:#333; color:white;}
        button {background:#e50914; color:white; cursor:pointer; transition:0.3s;}
        button:hover {background:#ff1a1a;}
        table {width:100%; border-collapse:collapse; margin-top:20px;}
        th, td {padding:10px; border-bottom:1px solid #444; text-align:left;}
        th {background:#222;}
        a.home-btn {display:block; margin-top:20px; text-align:center; color:#1a73e8; text-decoration:none;}
        a.home-btn:hover {text-decoration:underline;}
    </style>
</head>
<body>

<header>📦 INVENTORY MANAGEMENT SYSTEM</header>

<div class="container">
    <h2>Inquire Item</h2>

    <form method="GET" action="">
        <input type="text" name="search" placeholder="Enter item name or category..."
               value="<?= htmlspecialchars($search) ?>" required>
        <button type="submit">Search</button>
    </form>

    <?php if (!empty($results)): ?>
        <table>
            <tr>
                <th>Item Name</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Price ($)</th>
            </tr>
            <?php foreach ($results as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['item_name']) ?></td>
                    <td><?= htmlspecialchars($item['category']) ?></td>
                    <td><?= htmlspecialchars($item['quantity']) ?></td>
                    <td><?= number_format($item['price'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

    <?php elseif ($search !== ''): ?>
        <p>No items found matching "<?= htmlspecialchars($search) ?>".</p>
    <?php endif; ?>

    <a class="home-btn" href="index.php">🏠 Back to Home</a>
</div>

</body>
</html>
