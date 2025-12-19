<?php
include(__DIR__ . '/db.php'); // make sure db.php is in the same folder

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitize and get POST data
    $id        = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $item_name = isset($_POST['item_name']) ? trim($_POST['item_name']) : '';
    $category  = isset($_POST['category']) ? trim($_POST['category']) : '';
    $quantity  = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;
    $price     = isset($_POST['price']) ? floatval($_POST['price']) : 0;

    // Validate all fields
    if (empty($id) || empty($item_name) || empty($category) || empty($quantity) || empty($price)) {
        echo "<script>alert('All fields are required!'); window.history.back();</script>";
        exit();
    }

    // Prepare the update statement
    $stmt = $connect->prepare("UPDATE items SET item_name=?, category=?, quantity=?, price=? WHERE id=?");

    if (!$stmt) {
        die("Prepare failed: " . $connect->error);
    }

    // Bind parameters (ssidi = string, string, integer, double, integer)
    $stmt->bind_param("ssidi", $item_name, $category, $quantity, $price, $id);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>alert('Item updated successfully!'); window.location='items_list.php';</script>";
    } else {
        echo "<script>alert('Failed to update item: " . $stmt->error . "'); window.history.back();</script>";
    }

    // Close statement and connection
    $stmt->close();
    $connect->close();

} else {
    // Redirect if accessed directly
    header("Location: items_list.php");
    exit();
}
?>
