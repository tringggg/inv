<?php
include 'db.php';

// Check if 'id' is passed
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('Sale ID is missing!'); window.location='sales_list.php';</script>";
    exit();
}

$sale_id = intval($_GET['id']);

// Fetch the sale to restore stock
$stmt = $connect->prepare("SELECT item_id, quantity FROM sales WHERE id = ?");
$stmt->bind_param("i", $sale_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('Sale not found!'); window.location='sales_list.php';</script>";
    exit();
}

$sale = $result->fetch_assoc();
$stmt->close();

// Start transaction
$connect->begin_transaction();

try {
    // Delete the sale
    $delete_stmt = $connect->prepare("DELETE FROM sales WHERE id = ?");
    $delete_stmt->bind_param("i", $sale_id);
    $delete_stmt->execute();
    $delete_stmt->close();

    // Restore item stock
    $update_stmt = $connect->prepare("UPDATE items SET quantity = quantity + ? WHERE id = ?");
    $update_stmt->bind_param("ii", $sale['quantity'], $sale['item_id']);
    $update_stmt->execute();
    $update_stmt->close();

    $connect->commit();

    echo "<script>alert('Sale deleted successfully!'); window.location='sales_list.php';</script>";
} catch (Exception $e) {
    $connect->rollback();
    echo "<script>alert('Failed to delete sale: ".$e->getMessage()."'); window.location='sales_list.php';</script>";
}

$connect->close();
?>
