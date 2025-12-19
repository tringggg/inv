<?php
include 'db.php';
$id = $_GET['id'];

$connect->query("DELETE FROM items WHERE id = $id");

header("Location: items_list.php");
?>