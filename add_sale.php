<?php
include 'db.php';

// Fetch all items for dropdown
$items_result = $connect->query("SELECT * FROM items ORDER BY item_name ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitize POST data
    $item_id   = isset($_POST['item_id']) ? intval($_POST['item_id']) : 0;
    $quantity  = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;
    $sale_date = isset($_POST['sale_date']) ? $_POST['sale_date'] : '';

    // Validate input
    if ($item_id <= 0 || $quantity <= 0 || empty($sale_date)) {
        echo "<script>alert('All fields are required!'); window.history.back();</script>";
        exit();
    }

    // Get item price & stock
    $stmt = $connect->prepare("SELECT price, quantity FROM items WHERE id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();
    $stmt->close();

    if (!$item) {
        echo "<script>alert('Item not found!'); window.history.back();</script>";
        exit();
    }

    if ($quantity > $item['quantity']) {
        echo "<script>alert('Not enough stock available!'); window.history.back();</script>";
        exit();
    }

    $total_price = $item['price'] * $quantity;

    // Insert sale
    $stmt = $connect->prepare(
        "INSERT INTO sales (item_id, quantity, total_price, sale_date)
         VALUES (?, ?, ?, ?)"
    );
    $stmt->bind_param("iids", $item_id, $quantity, $total_price, $sale_date);

    if ($stmt->execute()) {

        // Update stock
        $stmt2 = $connect->prepare(
            "UPDATE items SET quantity = quantity - ? WHERE id = ?"
        );
        $stmt2->bind_param("ii", $quantity, $item_id);
        $stmt2->execute();
        $stmt2->close();

        echo "<script>alert('Sale added successfully!'); window.location='sales_list.php';</script>";

    } else {
        echo "<script>alert('Failed to add sale: ".$stmt->error."'); window.history.back();</script>";
    }

    $stmt->close();
    $connect->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Sale - Inventory & Sales Management System</title>
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
            width: 90%;
            max-width: 500px;
            margin: 50px auto;
            background: #1c1c1c;
            padding: 30px;
            border-radius: 15px;
            border: 2px solid #333;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #1e90ff;
            padding-bottom: 10px;
        }

        form select,
        form input,
        form button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #333;
            background: #222;
            color: white;
            font-size: 16px;
        }

        form button {
            background: #1e90ff;
            border: none;
            cursor: pointer;
        }

        form button:hover {
            background: #4285f4;
        }

        .btn-back {
            display: inline-block;
            margin-bottom: 15px;
            padding: 10px 20px;
            background: #1a73e8;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<header>💰 INVENTORY & SALES MANAGEMENT</header>

<div class="container">
    <a href="sales_list.php" class="btn-back">← Back to Sales</a>
    <h1>Add Sale</h1>

    <form method="POST">
        <label>Select Item:</label>
        <select name="item_id" required>
            <option value="">-- Choose an item --</option>
            <?php while ($item = $items_result->fetch_assoc()) { ?>
                <option value="<?= $item['id'] ?>">
                    <?= htmlspecialchars($item['item_name']) ?> (Stock: <?= $item['quantity'] ?>)
                </option>
            <?php } ?>
        </select>

        <label>Quantity Sold:</label>
        <input type="number" name="quantity" min="1" required>

        <label>Sale Date:</label>
        <input type="date" name="sale_date" required>

        <button type="submit">Add Sale</button>
    </form>
</div>

</body>
</html>
