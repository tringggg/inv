<?php
include 'db.php';

// Check if 'id' is passed
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('Sale ID is missing!'); window.location='sales_list.php';</script>";
    exit();
}

$sale_id = intval($_GET['id']);

// Fetch sale info
$stmt = $connect->prepare("SELECT * FROM sales WHERE id = ?");
$stmt->bind_param("i", $sale_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('Sale not found!'); window.location='sales_list.php';</script>";
    exit();
}

$sale = $result->fetch_assoc();
$stmt->close();

// Fetch all items for the dropdown
$items_result = $connect->query("SELECT * FROM items ORDER BY item_name ASC");

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = isset($_POST['item_id']) ? intval($_POST['item_id']) : 0;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;
    $sale_date = isset($_POST['sale_date']) ? $_POST['sale_date'] : '';

    if ($item_id <= 0 || $quantity <= 0 || empty($sale_date)) {
        echo "<script>alert('All fields are required!'); window.history.back();</script>";
        exit();
    }

    // Get item price & current stock
    $stmt = $connect->prepare("SELECT price, quantity FROM items WHERE id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $item_result = $stmt->get_result();
    $item = $item_result->fetch_assoc();
    $stmt->close();

    if (!$item) {
        echo "<script>alert('Item not found!'); window.history.back();</script>";
        exit();
    }

    $old_quantity = $sale['quantity']; // original sold quantity
    $stock_after_adjustment = $item['quantity'] + $old_quantity; // return old quantity to stock
    if ($quantity > $stock_after_adjustment) {
        echo "<script>alert('Not enough stock available!'); window.history.back();</script>";
        exit();
    }

    $total_price = $item['price'] * $quantity;

    // Update sale
    $update_stmt = $connect->prepare("UPDATE sales SET item_id=?, quantity=?, total_price=?, sale_date=? WHERE id=?");
    $update_stmt->bind_param("iidsi", $item_id, $quantity, $total_price, $sale_date, $sale_id);

    if ($update_stmt->execute()) {
        // Update stock
        $stmt2 = $connect->prepare("UPDATE items SET quantity=? WHERE id=?");
        $stmt2->bind_param("ii", $stock_after_adjustment_minus_new, $item_id);
        $stock_after_adjustment_minus_new = $stock_after_adjustment - $quantity;
        $stmt2->execute();
        $stmt2->close();

        echo "<script>alert('Sale updated successfully!'); window.location='sales_list.php';</script>";
    } else {
        echo "<script>alert('Failed to update sale: ".$update_stmt->error."'); window.history.back();</script>";
    }

    $update_stmt->close();
    $connect->close();
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Sale - Inventory & Sales Management System</title>
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
        form input[type="number"],
        form input[type="date"],
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
    <h1>Update Sale</h1>

    <form method="POST" action="">
        <label for="item_id">Select Item:</label>
        <select name="item_id" id="item_id" required>
            <option value="">-- Choose an item --</option>
            <?php while ($item = $items_result->fetch_assoc()) { ?>
                <option value="<?= $item['id'] ?>" <?= $item['id'] == $sale['item_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($item['item_name']) ?> (Stock: <?= $item['quantity'] ?>)
                </option>
            <?php } ?>
        </select>

        <label for="quantity">Quantity Sold:</label>
        <input type="number" name="quantity" id="quantity" value="<?= $sale['quantity'] ?>" min="1" required>

        <label for="sale_date">Sale Date:</label>
        <input type="date" name="sale_date" id="sale_date" value="<?= $sale['sale_date'] ?>" required>

        <button type="submit">Update Sale</button>
    </form>
</div>

</body>
</html>
