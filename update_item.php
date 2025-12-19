<?php
include 'db.php';

// Check if the connection variable ($connect) is defined and valid
if (!isset($connect) || $connect->connect_error) {
    error_log("DB Connection failed in update_item.php");
    echo "<script>alert('Critical Database Connection Error!'); window.location='items_list.php';</script>";
    exit();
}

// Validate ID from GET
$item_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if($item_id <= 0){
    echo "<script>alert('Invalid Item ID! Please provide a valid item ID.'); window.location='items_list.php';</script>";
    exit();
}

// Fetch item
$stmt = $connect->prepare("SELECT * FROM items WHERE id=?");
if ($stmt === false) {
    error_log("Prepare failed: " . $connect->error);
    echo "<script>alert('Database error during item fetch (Prepare failed)!'); window.location='items_list.php';</script>";
    exit();
}

$stmt->bind_param("i", $item_id);
if (!$stmt->execute()) {
    error_log("Execute failed: " . $stmt->error);
    echo "<script>alert('Database error during item fetch (Execute failed)!'); window.location='items_list.php';</script>";
    exit();
}

$result = $stmt->get_result();

if($result->num_rows === 0){
    echo "<script>alert('Item not found!'); window.location='items_list.php';</script>";
    exit();
}

$item = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Item - Inventory Management System</title>
    <style>
        body { margin:0; padding:0; background:#111; font-family:Arial,sans-serif; color:white; }
        header { background:#1e90ff; padding:20px; text-align:center; font-size:32px; font-weight:bold; }
        .container { width:90%; max-width:500px; margin:50px auto; background:#1c1c1c; padding:30px; border-radius:15px; border:2px solid #333; }
        h1 { text-align:center; margin-bottom:20px; border-bottom:2px solid #1e90ff; padding-bottom:10px; }
        form input, form select { width:100%; padding:10px; margin:10px 0; border-radius:8px; border:1px solid #333; background:#222; color:white; font-size:16px; }
        form button { width:100%; padding:12px; margin-top:15px; border:none; border-radius:10px; background:#1e90ff; color:white; font-size:18px; cursor:pointer; }
        form button:hover { background:#3399ff; }
        a.home-btn { display:block; text-align:center; margin-top:15px; color:#1a73e8; text-decoration:none; }
        a.home-btn:hover { text-decoration:underline; }
        footer { margin-top:50px; text-align:center; font-size:14px; color:#777; }
    </style>
</head>
<body>

<header>📦 INVENTORY MANAGEMENT SYSTEM</header>

<div class="container">
    <h1>Update Item</h1>

    <form action="update_item_process.php" method="POST">
        <input type="hidden" name="id" value="<?= $item['id'] ?>">
        <input type="text" name="item_name" placeholder="Item Name" value="<?= htmlspecialchars($item['item_name']) ?>" required>
        <input type="text" name="category" placeholder="Category" value="<?= htmlspecialchars($item['category']) ?>" required>
        <input type="number" name="quantity" placeholder="Quantity" value="<?= $item['quantity'] ?>" required>
        <input type="number" step="0.01" name="price" placeholder="Price per Unit" value="<?= $item['price'] ?>" required>
        <button type="submit">Update Item</button>
    </form>

    <a class="home-btn" href="items_list.php">🏠 Back to Items List</a>
</div>

<footer>© <?= date("Y") ?> Inventory Management System</footer>

</body>
</html>

<?php
if (isset($connect)) {
    $connect->close();
}
?>
