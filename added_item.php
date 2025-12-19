<?php
include 'db.php';

// Get POST data and sanitize
$item_name = isset($_POST['item_name']) ? trim($_POST['item_name']) : '';
$category  = isset($_POST['category']) ? trim($_POST['category']) : '';
$quantity  = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;
$price     = isset($_POST['price']) ? floatval($_POST['price']) : 0;

// Validate required fields
if (empty($item_name) || empty($category) || empty($quantity) || empty($price)) {
    echo "<script>alert('All fields are required!'); window.history.back();</script>";
    exit();
}

// Prepare the INSERT statement
$stmt = $connect->prepare("INSERT INTO items (item_name, category, quantity, price) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssid", $item_name, $category, $quantity, $price);

// Execute and check
$success = $stmt->execute();

$stmt->close();
$connect->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Item Added</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #111;
            font-family: Arial, sans-serif;
            color: white;
            text-align: center;
        }

        header {
            background: #1e90ff;
            padding: 20px;
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .container {
            width: 90%;
            max-width: 500px;
            margin: 60px auto;
            background: #1c1c1c;
            padding: 30px;
            border-radius: 15px;
            border: 2px solid #333;
        }

        h2 {
            margin-bottom: 20px;
            text-transform: uppercase;
            border-bottom: 2px solid #1e90ff;
            padding-bottom: 10px;
        }

        .message {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            border-radius: 10px;
            background: #1e90ff;
            color: white;
            font-size: 16px;
            text-decoration: none;
            transition: 0.3s;
        }

        .btn:hover {
            background: #3399ff;
        }

        footer {
            margin-top: 50px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>

<header>📦 INVENTORY MANAGEMENT SYSTEM</header>

<div class="container">
    <h2>Item Added</h2>

    <?php if ($success): ?>
        <div class="message">Item "<strong><?= htmlspecialchars($item_name) ?></strong>" has been added successfully!</div>
        <a href="items_list.php" class="btn">View Items</a>
    <?php else: ?>
        <div class="message">Failed to add item. Please try again.</div>
        <a href="add_item.php" class="btn">Back to Add Item</a>
    <?php endif; ?>
</div>

<footer>© <?= date("Y") ?> Inventory Management System</footer>

</body>
</html>
